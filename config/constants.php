<?php
// Prevent direct access
if (php_sapi_name() !== 'cli' && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
	http_response_code(403);
	exit('Forbidden');
}

// Application info
define('APP_NAME', 'R.Kastrati Galabau');
define('APP_VERSION', '0.1.0');

// Base URL (can be overridden via environment variable)
$envBaseUrl = getenv('BASE_URL');
if ($envBaseUrl) {
    define('BASE_URL', rtrim($envBaseUrl, '/'));
} elseif (php_sapi_name() !== 'cli' && !empty($_SERVER['HTTP_HOST'])) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? '127.0.0.1:8000';
    define('BASE_URL', $scheme . '://' . $host);
} else {
    define('BASE_URL', 'http://127.0.0.1:8000');
}

// Paths (server-side and URL parts)
define('ADMIN_PATH', '/admin');
define('AUTH_PATH', '/auth');
define('UPLOAD_PATH', __DIR__ . '/../uploads');
define('UPLOAD_URL', rtrim(BASE_URL, '/') . '/uploads');
define('GALLERY_PATH', UPLOAD_PATH . '/gallery');
define('ABOUT_PATH', UPLOAD_PATH . '/about');
define('HOMEPAGE_PATH', UPLOAD_PATH . '/homepage');

// Upload rules
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5 MB
define('ALLOWED_IMAGE_TYPES', serialize([
	'image/jpeg',
	'image/png',
	'image/webp'
]));

