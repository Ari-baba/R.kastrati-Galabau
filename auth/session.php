<?php
require_once __DIR__ . '/../config/constants.php';

function admin_login_url($extra = '')
{
    $url = rtrim(BASE_URL, '/') . '/auth/login.php';
    if ($extra !== '') {
        $url .= '?' . ltrim($extra, '?&');
    }
    return $url;
}

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

function login_user($admin_id, $username)
{
    session_regenerate_id(true);
    $_SESSION['admin'] = [
        'id' => $admin_id,
        'username' => $username,
        'last_activity' => time()
    ];
}

function is_logged_in()
{
    return !empty($_SESSION['admin']) && isset($_SESSION['admin']['id']);
}

function require_login()
{
    if (!is_logged_in()) {
        header('Location: ' . admin_login_url());
        exit;
    }
    enforce_session_timeout();
}

function enforce_session_timeout($timeout = null)
{
    $timeout = $timeout ?? (int)getenv('SESSION_TIMEOUT') ?: 1800;
    if (!empty($_SESSION['admin']['last_activity']) && (time() - $_SESSION['admin']['last_activity']) > $timeout) {
        session_unset();
        session_destroy();
        header('Location: ' . admin_login_url('timeout=1'));
        exit;
    }
    $_SESSION['admin']['last_activity'] = time();
}

function logout_user()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'] ?? '', $params['secure'] ?? false, $params['httponly'] ?? false);
    }
    session_destroy();
}
