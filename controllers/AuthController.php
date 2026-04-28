<?php

class AuthController extends BaseController
{
    private AuthService $authService;

    public function __construct(?AuthService $authService = null)
    {
        $this->authService = $authService ?? new AuthService();
    }

    public function index(): void
    {
        if (isLoggedIn()) {
            if (!isAdmin()) {
                $this->authService->logout();
                setFlash('auth_error', 'Je hebt geen toegang tot dit admin-paneel.');
            } else {
                redirect(appUrl('admin'));
            }
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::check();

            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            rememberInput(['email' => $email]);

            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Vul een geldig e-mailadres in.';
            } elseif ($password === '') {
                $error = 'Vul je wachtwoord in.';
            } else {
                $loginResult = $this->authService->attemptLogin($email, $password);
                
                if ($loginResult === true) {
                    clearOldInput();
                    redirect(appUrl('admin'));
                } elseif (is_array($loginResult)) {
                    $error = $loginResult['message'] ?? 'Ongeldige inloggegevens.';
                } else {
                    $error = 'Ongeldige inloggegevens.';
                }
            }
        }

        $error ??= getFlash('auth_error');

        $this->render('auth/login', [
            'error' => $error,
            'email' => old('email'),
        ]);
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            throw new HttpException(405, 'Methode niet toegestaan');
        }

        CSRF::check();
        $this->authService->logout();
        redirect(appUrl('login'));
    }
}
