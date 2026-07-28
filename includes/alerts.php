<?php
if (php_sapi_name() !== 'cli' && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    http_response_code(403);
    exit('Forbidden');
}

function alert_message($type, $text)
{
    $class = 'alert-info';
    switch ($type) {
        case 'success':
            $class = 'alert-success';
            break;
        case 'warning':
            $class = 'alert-warning';
            break;
        case 'error':
            $class = 'alert-danger';
            break;
    }
    return sprintf('<div class="alert %s">%s</div>', htmlspecialchars($class, ENT_QUOTES, 'UTF-8'), htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
}

function render_alerts()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $alerts = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);
    foreach ($alerts as $alert) {
        echo alert_message($alert['type'] ?? 'info', $alert['text'] ?? '');
    }
}
