<?php

require_once __DIR__ . '/debug.php';
require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/htmlprocessing.php';
require_once __DIR__ . '/db/db.php';
require_once __DIR__ . '/redirect.php';
require_once __DIR__ . '/cookies.php';
require_once __DIR__ . '/campaign.php';

$clickid = (string)($_GET['clickid'] ?? '');
$stepRaw = (string)($_GET['step'] ?? '');

if ($clickid === '' || $stepRaw === '' || !preg_match('/^\d+$/', $stepRaw)) {
    die('CONTEXTO PRÓXIMO INVÁLIDO!');
}

$currentStep = (int)$stepRaw;

global $db;
$click = $db->get_click_by_clickid($clickid);
if (empty($click)) {
    die('CLIQUE NÃO ENCONTRADO!');
}

set_clickid($clickid);

$campId = (int)$click['campaign_id'];
$settings = $db->get_campaign_settings($campId);
$c = new Campaign($campId, $settings);

$flowName = (string)($click['flow'] ?? '');
$flow = null;
foreach ($c->black->flows as $f) {
    if ($f->name === $flowName) {
        $flow = $f;
        break;
    }
}
if ($flow === null) {
    die('FLUXO NÃO ENCONTRADO!');
}

$steps = $flow->steps;
$maxReachedStep = (int)($click['step'] ?? 0);
if ($currentStep > $maxReachedStep) {
    die('CONTEXTO DE ETAPA INVÁLIDO!');
}

$nextStep = $currentStep + 1;
if ($nextStep >= count($steps)) {
    die('NÃO HÁ MAIS ETAPAS NO FUNIL!');
}

$plannedPath = $click['path'] ?? [];
if (!is_array($plannedPath) || empty($plannedPath)) {
    die('CAMINHO PLANEJADO VAZIO!');
}

if (!isset($plannedPath[$nextStep])) {
    die('NENHUMA VARIANTE PLANEJADA PARA A ETAPA ' . $nextStep);
}

$chosenVariant = $plannedPath[$nextStep];
$stepSettings = $steps[$nextStep];
$validItems = $stepSettings->getItems();
if (!in_array($chosenVariant, $validItems, true)) {
    if (empty($validItems)) {
        die('NENHUM ITEM DISPONÍVEL PARA A ETAPA ' . $nextStep);
    }
    $chosenVariant = $validItems[0];
    $plannedPath[$nextStep] = $chosenVariant;
    $db->update_click_path($clickid, $plannedPath);
}

if ($maxReachedStep < $nextStep) {
    if (!$db->add_click_step($clickid, $nextStep, $chosenVariant)) {
        die('FALHA AO REGISTRAR ENTRADA DE ETAPA!');
    }
}

if ($stepSettings->isRedirect()) {
    $url = $stepSettings->getRedirectUrlByLabel($chosenVariant);
    $mp = new MacrosProcessor($c, null, $clickid, $click['userid'] ?? null);
    $url = $mp->replace_url_macros($url);
    redirect($url, $stepSettings->redirectType, false);
    return;
}

if ($stepSettings->isDirectLoad($chosenVariant)) {
    redirect(get_directload_step_url($clickid, $nextStep), 302, false);
    return;
}

echo load_step($c, $flow, $nextStep, $chosenVariant, $clickid, false);
