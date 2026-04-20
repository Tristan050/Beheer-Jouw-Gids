<?php

ob_start();

if (session_status() === PHP_SESSION_NONE) {
    $isHttps = (
        (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
        (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
    );

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => $isHttps,
        'cookie_samesite' => 'Lax',
    ]);
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

require_once __DIR__ . '/libs/HttpException.php';
require_once __DIR__ . '/libs/CSRF.php';
require_once __DIR__ . '/libs/functions_dbs_mysqli.php';
require_once __DIR__ . '/libs/helper.php';
require_once __DIR__ . '/libs/loadPage.php';

CSRF::init();

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    Logger::getInstance()->error($errstr, [
        'file' => $errfile,
        'line' => $errline,
        'code' => $errno,
    ]);
    return false;
});

set_exception_handler(function (Throwable $e) {
    Logger::getInstance()->error($e->getMessage(), [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
    ]);
    http_response_code(500);
    require __DIR__ . '/views/errors/500.php';
    exit();
});

header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("X-Frame-Options: SAMEORIGIN");
