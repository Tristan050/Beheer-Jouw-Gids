<?php

class CSRF
{
    public static function init()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function token()
    {
        self::init();
        return '<input type="hidden" id="csrf_token" name="csrf_token" value="' .
            htmlspecialchars($_SESSION['csrf_token']) . '">';
    }

    public static function check($token = null)
    {
        self::init();

        if ($token === null) {
            $token = $_POST['csrf_token'] ?? null;
        }

        $sessionToken = $_SESSION['csrf_token'] ?? null;
        $isValidToken = is_string($token) && is_string($sessionToken) && hash_equals($sessionToken, $token);

        if (!$isValidToken) {
            http_response_code(403);

            setFlash('auth_error', 'Je sessie is verlopen of ongeldig. Probeer opnieuw.');
            redirect(appUrl('login'));
            exit;
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        return true;
    }



    public static function reset()
    {
        unset($_SESSION['csrf_token']);
    }
}
