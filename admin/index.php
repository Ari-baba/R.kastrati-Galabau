<?php
require_once __DIR__ . '/../auth/check_auth.php';

header('Location: ' . rtrim(BASE_URL, '/') . ADMIN_PATH . '/dashboard.php');
exit;
