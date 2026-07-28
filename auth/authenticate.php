<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: login.php');
	exit;
}

// Basic input validation
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$csrf = $_POST['csrf_token'] ?? '';

if (!validate_csrf_token($csrf)) {
	header('Location: login.php?error=' . urlencode('CSRF token invalid'));
	exit;
}

$errorMessage = 'Invalid credentials';
if ($username === '' || $password === '') {
    $errorMessage = 'Të gjitha fushat janë të detyrueshme.';
} elseif (!validate_csrf_token($csrf)) {
    $errorMessage = 'CSRF token invalid';
} else {
    try {
        $stmt = db_query('SELECT id, username, password FROM admins WHERE username = :u LIMIT 1', ['u' => $username]);
        $row = $stmt->fetch();
        if ($row && password_verify($password, $row['password'])) {
            login_user($row['id'], $row['username']);
            header('Location: ' . rtrim(BASE_URL, '/') . ADMIN_PATH . '/dashboard.php');
            exit;
        }
        error_log('Failed login for username: ' . $username . ' from ' . ($_SERVER['REMOTE_ADDR'] ?? 'CLI'));
        $errorMessage = 'Kredencialet janë të pasakta. Provo përsëri.';
    } catch (Exception $e) {
        error_log('Auth error: ' . $e->getMessage());
        $errorMessage = 'Gabim i brendshëm. Provo përsëri më vonë.';
    }
}

header('Location: ' . rtrim(BASE_URL, '/') . '/auth/login.php?error=' . urlencode($errorMessage));
exit;

