<?php

class OTPController extends BaseController
{
    private OTPService $otpService;
    private UserRepository $userRepository;

    public function __construct(?OTPService $otpService = null, ?UserRepository $userRepository = null)
    {
        $this->otpService = $otpService ?? new OTPService();
        $this->userRepository = $userRepository ?? new UserRepository();
    }

    public function loginForm(): void
    {
        redirect(appUrl('login'));
    }

    public function verifyCode(): void
    {
        if (!isset($_SESSION['otp_email'])) {
            redirect(appUrl('login'));
        }

        if (isLoggedIn()) {
            redirect(appUrl('admin'));
        }

        $error = null;
        $email = $_SESSION['otp_email'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'verify_code') {
            CSRF::check();

            $code = trim((string) ($_POST['code'] ?? ''));

            if ($code === '') {
                $error = 'Vul de verificatiecode in.';
            } else {
                $validationResult = $this->otpService->validateCode($email, $code);

                if (!empty($validationResult['success'])) {
                    $user = $this->userRepository->findActiveUserByEmail($email);

                    if ($user === null) {
                        $error = 'Gebruiker niet gevonden.';
                    } else {
                        $userRoles = $user['roles'] ?? [];
                        if (!array_intersect($userRoles, ['super_admin', 'admin'])) {
                            $error = 'Je hebt geen toegang tot dit admin-paneel.';
                        } else {
                            $this->otpService->clearRequestAttempts($email);
                            session_regenerate_id(true);

                            $_SESSION['user_id'] = (int) $user['id'];
                            $_SESSION['user_roles'] = $user['roles'] ?? [];
                            $_SESSION['user_verified'] = (bool) ($user['is_verified'] ?? false);

                            unset($_SESSION['otp_email']);
                            unset($_SESSION['otp_debug_code']);

                            redirect(appUrl('admin'));
                        }
                    }
                } else {
                    $error = $validationResult['message'] ?? 'Ongeldige of verlopen verificatiecode.';
                }
            }
        }

        $debugCode = $_SESSION['otp_debug_code'] ?? null;
        
        $this->render('auth/otp-verify', [
            'error' => $error,
            'email' => $email,
            'debug_code' => $debugCode,
            'debug_mode' => jg_db_debug_enabled(),
        ]);
    }

    public function logout(): void
    {
        $this->requirePost();
        CSRF::check();
        unset($_SESSION['otp_email']);
        unset($_SESSION['otp_debug_code']);
        session_destroy();
        redirect(appUrl('login'));
    }
}
