<?php
if (php_sapi_name() !== 'cli' && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    http_response_code(403);
    exit('Forbidden');
}

require_once __DIR__ . '/../auth/session.php';
require_once __DIR__ . '/../config/constants.php';
logout_user();
header('Location: ' . admin_login_url());
exit;
