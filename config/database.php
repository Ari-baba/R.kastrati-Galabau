<?php
// Prevent direct access
if (php_sapi_name() !== 'cli' && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
	http_response_code(403);
	exit('Forbidden');
}

/**
 * Database configuration and connection helper.
 * Credentials may be provided via environment variables for security.
 */

$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_NAME = getenv('DB_NAME') ?: 'rezervim';
$DB_USER = getenv('DB_USER') ?: 'mysql80';
$DB_PASS = getenv('DB_PASS') ?: '12345678';
$DB_CHARSET = 'utf8mb4';

function get_pdo()
{
	static $pdo = null;
	if ($pdo instanceof PDO) {
		return $pdo;
	}

	global $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS, $DB_CHARSET;
	$dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHARSET}";
	try {
		$pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false,
		]);
		return $pdo;
	} catch (PDOException $e) {
		// In production do not expose details
		error_log('DB connection error: ' . $e->getMessage());
		if (getenv('APP_ENV') === 'development' || php_sapi_name() === 'cli') {
			throw $e;
		}
		http_response_code(500);
		exit('Database connection error');
	}
}

// Convenience: small wrapper to prepare and execute prepared statements
function db_query($sql, $params = [])
{
	$stmt = get_pdo()->prepare($sql);
	$stmt->execute($params);
	return $stmt;
}

