<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../config/constants.php';

function redirect_to_login($extra = '')
{
    $url = rtrim(BASE_URL, '/') . '/auth/login.php';
    if ($extra !== '') {
        $url .= '?' . ltrim($extra, '?&');
    }
    header('Location: ' . $url);
    exit;
}

if (!is_logged_in()) {
    redirect_to_login();
}

enforce_session_timeout();
