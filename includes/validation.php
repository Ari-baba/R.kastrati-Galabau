<?php
if (php_sapi_name() !== 'cli' && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    http_response_code(403);
    exit('Forbidden');
}

function is_valid_name($value)
{
    return preg_match('/^[A-Za-zÇçËëÖöÜüÏïËëáéíóúÁÉÍÓÚÝýÑñ ]{2,}$/u', $value);
}

function is_valid_phone($value)
{
    return preg_match('/^[0-9\s+\-]{7,20}$/', $value);
}

function is_required($value)
{
    return trim((string)$value) !== '';
}

function is_valid_upload_image($file)
{
    $allowed = config('upload.allowed_types');
    if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    if ($file['size'] > config('upload.max_size')) {
        return false;
    }
    $mime = mime_content_type($file['tmp_name']);
    return in_array($mime, $allowed, true);
}
