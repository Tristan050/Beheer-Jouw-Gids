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
require_once __DIR__ . '/libs/helper.php';
require_once __DIR__ . '/controllers/BaseController.php';
require_once __DIR__ . '/libs/loadPage.php';

CSRF::init();

header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("X-Frame-Options: SAMEORIGIN");
