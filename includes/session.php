<?php
if (php_sapi_name() !== 'cli' && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    http_response_code(403);
    exit('Forbidden');
}

function includes_start_session()
{
    if (session_status() === PHP_SESSION_NONE) {
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        session_start();
    }
}

function set_flash_message($type, $text)
{
    includes_start_session();
    $_SESSION['flash_messages'][] = ['type' => $type, 'text' => $text];
}

function get_flash_messages()
{
    includes_start_session();
    $messages = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);
    return $messages;
}

function is_session_active()
{
    includes_start_session();
    return !empty($_SESSION);
}
