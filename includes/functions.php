<?php
if (php_sapi_name() !== 'cli' && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    http_response_code(403);
    exit('Forbidden');
}

require_once __DIR__ . '/alerts.php';

function sanitize_text($value)
{
    return trim(filter_var($value, FILTER_SANITIZE_STRING));
}

function redirect_to($url)
{
    header('Location: ' . $url);
    exit;
}

function format_datetime($datetime, $format = 'Y-m-d H:i:s')
{
    $dt = new DateTime($datetime);
    return $dt->format($format);
}

function flash_set($key, $message)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash_messages'][$key] = $message;
}

function flash_get($key)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!empty($_SESSION['flash_messages'][$key])) {
        $message = $_SESSION['flash_messages'][$key];
        unset($_SESSION['flash_messages'][$key]);
        return $message;
    }
    return null;
}

function display_flash($key)
{
    $message = flash_get($key);
    if ($message !== null) {
        echo alert_message($message['type'] ?? 'info', $message['text'] ?? '');
    }
}
