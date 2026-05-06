<?php
require_once __DIR__ . '/../logging.php';
require_once __DIR__ . '/../db/db.php';

global $db;

$clickid = $_GET['clickid'] ?? '';
if ($clickid === '') {
    http_response_code(400);
    $msg = 'Nenhum clickid fornecido nos parâmetros da URL';
    add_log('updateparams', $msg);
    echo $msg;
    exit;
}

try {
    $click = $db->get_click_by_clickid($clickid);
    if (empty($click)) {
        http_response_code(404);
        $msg = 'Nenhum clique encontrado para clickid: ' . $clickid;
        add_log('updateparams', $msg);
        echo $msg;
        exit;
    }

    $urlParams = $_GET;
    unset($urlParams['clickid']);

    if (empty($urlParams)) {
        http_response_code(200);
        $msg = 'Nenhum parâmetro para atualizar para clickid: ' . $clickid;
        add_log('updateparams', $msg);
        echo $msg;
        exit;
    }

    $existingParams = $click['params'] ?? [];
    foreach ($urlParams as $key => $value) {
        $existingParams[$key] = $value;
    }

    $updated = $db->update_click_params($click['id'], $existingParams);
    if ($updated) {
        http_response_code(200);
        $updatedKeys = array_keys($urlParams);
        $msg = 'Parâmetros atualizados com sucesso para clickid: ' . $clickid . '. Chaves atualizadas: ' . implode(', ', $updatedKeys);
        add_log('updateparams', $msg);
        echo $msg;
    } else {
        http_response_code(500);
        $msg = 'Falha ao atualizar parâmetros para clickid: ' . $clickid;
        add_log('updateparams', $msg);
        echo $msg;
    }
} catch (Exception $e) {
    http_response_code(500);
    $msg = 'Erro ao atualizar parâmetros para clickid ' . $clickid . ': ' . $e->getMessage();
    add_log('updateparams', $msg);
    echo $msg;
}
