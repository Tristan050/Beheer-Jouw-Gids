<?php

class AuthController extends BaseController
{
    private AuthService $authService;
    private OTPService $otpService;

    public function __construct(?AuthService $authService = null, ?OTPService $otpService = null)
    {
        $this->authService = $authService ?? new AuthService();
        $this->otpService = $otpService ?? new OTPService();
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
                
                if (is_array($loginResult) && !empty($loginResult['success'])) {
                    session_regenerate_id(true);

                    $_SESSION['otp_email'] = $email;

                    $otpResult = $this->otpService->generateAndSendCode($email);
                    
                    if ($otpResult['success']) {
                        // Store debug code in session if in debug mode
                        if ($otpResult['debug'] && $otpResult['code']) {
                            $_SESSION['otp_debug_code'] = $otpResult['code'];
                        }
                        clearOldInput();
                        redirect(appUrl('otp-verify'));
                    }

                    $error = $this->otpService->getLastError() ?? 'Kon verificatiecode niet versturen. Controleer de logs.';
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
        $this->requirePost();
        CSRF::check();
        $this->authService->logout();
        redirect(appUrl('login'));
    }
}
