<?php

$db_host = getenv("DB_HOST");
$db_dbs = getenv("DB_NAME");
$db_user = getenv("DB_USER");
$db_pass = getenv("DB_PASS");

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