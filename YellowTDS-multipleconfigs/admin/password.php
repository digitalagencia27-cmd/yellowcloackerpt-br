<?php
require_once __DIR__ . '/../logging.php';
require_once __DIR__ . '/../settings.php';
require_once __DIR__ . '/../cookies.php';
require_once __DIR__ . '/ratelimit.php';

function check_password($die = true): bool
{
    global $cloSettings;
    $pwd = $cloSettings['adminPassword'];
    $debug = $cloSettings['debug'];
    get_session();

    if (!empty($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
        add_log('trace','Already logged in!');
        return true;
    }else{
        if (empty($_SESSION["loggedin"]))
            add_log('trace','Loggedin is empty!');
        else if ($_SESSION["loggedin"]!==true)
            add_log('trace','Loggedin is not true!');
    }

    if (empty($pwd)){
        add_log('trace','Empty admin password in settings!');
        return true;
    }

    $ip = getip();
    $rl = check_rate_limit($ip);
    if (!$rl['allowed']) {
        $msg = "Muitas tentativas de login. Tente novamente em {$rl['retry_after']} segundos.";
        add_log('login', $msg, true);
        if ($die) {
            die($msg);
        } else {
            return false;
        }
    }

    if (!isset($_REQUEST['password'])) {
        $msg = "Nenhuma senha encontrada!";
        add_log("login", $msg, true);
        if ($die){
            die($msg);
        }else{
            return false;
        }
    }
    
    if (empty($_REQUEST['password'])) {
        $msg = "Senha vazia!";
        add_log("login", $msg, true);
        if ($die){
            die($msg);
        }else{
            return false;
        }
    }

    if ($_REQUEST['password'] !== $pwd) {
        record_failed_attempt($ip);
        $msg = "Senha incorreta!";
        add_log("login", $msg, true);
        if ($die){
            die($msg);
        }else{
            return false;
        }
    }

    rl_reset($ip);
        add_log("login", "Logado, configurando sessão.", true);
    $_SESSION['loggedin'] = true;
    session_write_close();
    return true;
}