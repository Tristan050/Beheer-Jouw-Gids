<?php

$db_host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
$db_dbs = $_ENV['DB_NAME'] ?? getenv('DB_NAME');
$db_user = $_ENV['DB_USER'] ?? getenv('DB_USER');
$db_pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS');

$appBaseUrl = rtrim((string) getenv('APP_BASE_URL'), '/');
$googleClientId = (string) getenv('GOOGLE_CLIENT_ID');
$googleClientSecret = (string) getenv('GOOGLE_CLIENT_SECRET');

if (!defined('APP_BASE_URL')) {
    define('APP_BASE_URL', $appBaseUrl);
}
if (!defined('GOOGLE_CLIENT_ID')) {
    define('GOOGLE_CLIENT_ID', $googleClientId);
}
if (!defined('GOOGLE_CLIENT_SECRET')) {
    define('GOOGLE_CLIENT_SECRET', $googleClientSecret);
}
if (!defined('GOOGLE_REDIRECT_URI')) {
    define('GOOGLE_REDIRECT_URI', APP_BASE_URL !== '' ? APP_BASE_URL . '/auth/google/callback' : '');
}