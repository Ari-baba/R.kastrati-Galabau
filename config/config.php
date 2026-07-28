<?php
// Prevent direct access
if (php_sapi_name() !== 'cli' && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
	http_response_code(403);
	exit('Forbidden');
}

require_once __DIR__ . '/constants.php';

// Timezone and date format
date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'Europe/Tirane');
define('DATE_FORMAT', 'Y-m-d H:i:s');

// Central config array
$__CONFIG = [
	'base_url' => BASE_URL,
	'app_name' => APP_NAME,
	'app_version' => APP_VERSION,
	'date_format' => DATE_FORMAT,
	'upload' => [
		'path' => UPLOAD_PATH,
		'url' => UPLOAD_URL,
		'max_size' => MAX_UPLOAD_SIZE,
		'allowed_types' => unserialize(ALLOWED_IMAGE_TYPES),
	],
];

/**
 * Get configuration value by key (dot notation supported).
 * Example: config('upload.max_size')
 */
function config($key = null, $default = null)
{
	global $__CONFIG;
	if ($key === null) {
		return $__CONFIG;
	}

	$parts = explode('.', $key);
	$value = $__CONFIG;
	foreach ($parts as $part) {
		if (is_array($value) && array_key_exists($part, $value)) {
			$value = $value[$part];
		} else {
			return $default;
		}
	}
	return $value;
}

