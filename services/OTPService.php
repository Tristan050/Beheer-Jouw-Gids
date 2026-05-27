<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class OTPService extends BaseService
{
    private const MAX_CODES_PER_WINDOW = 5;
    private const WINDOW_MINUTES = 10;
    private const BLOCK_MINUTES = 10;
    private const RATE_LIMIT_SCOPE = 'otp_request';
    private const MAX_VERIFY_ATTEMPTS = 5;
    private const VERIFY_WINDOW_MINUTES = 15;
    private const VERIFY_BLOCK_MINUTES = 30;
    private const VERIFY_RATE_LIMIT_SCOPE = 'otp_attempt';
    private const CODE_LENGTH = 6;
    private const EXPIRES_IN_MINUTES = 10;

    public function __construct(
        private readonly OTPRepository $repository = new OTPRepository(),
        private readonly RateLimitService $rateLimitService = new RateLimitService()
    ) {}

    /**
     * Generates a new OTP code for the given email and sends it via email.
     *
     * @param string $email The email address to send the OTP code to
     * @return array Array with keys: 'success' (bool), 'code' (string|null for debug), 'debug' (bool)
     */
    public function generateAndSendCode(string $email): array
    {
        $logger = Logger::getInstance();
        $email = strtolower(trim($email));
        $requestRateLimitKey = $this->getOtpRequestRateLimitKey($email);
        $attemptRateLimitKey = $this->getOtpAttemptRateLimitKey($email);
        $debugMode = jg_db_debug_enabled();

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $logger->warning('OTP request rejected for invalid email.', [
                'email' => $email,
            ]);
            return $this->response(false, 'Vul een geldig e-mailadres in.', [
                'code' => null,
                'debug' => false,
            ]);
        }

        if ($this->rateLimitService->isBlocked($attemptRateLimitKey, self::VERIFY_RATE_LIMIT_SCOPE)) {
            $minutesRemaining = $this->rateLimitService->getBlockedMinutesRemaining($attemptRateLimitKey, self::VERIFY_RATE_LIMIT_SCOPE);
            $logger->warning('OTP request blocked because verification attempts are blocked.', [
                'email' => $email,
                'blocked_minutes_remaining' => $minutesRemaining,
            ]);
            return $this->response(false, 'Je hebt te vaak een verificatiecode geprobeerd. Probeer het over ongeveer ' . $minutesRemaining . ' minuten opnieuw.', [
                'code' => null,
                'debug' => false,
            ]);
        }

        if ($this->rateLimitService->isBlocked($requestRateLimitKey, self::RATE_LIMIT_SCOPE)) {
            $minutesRemaining = $this->rateLimitService->getBlockedMinutesRemaining($requestRateLimitKey, self::RATE_LIMIT_SCOPE);
            $logger->warning('OTP request rate-limited.', [
                'email' => $email,
                'blocked_minutes_remaining' => $minutesRemaining,
            ]);
            return $this->response(false, 'Je hebt te vaak een code aangevraagd. Probeer het over ongeveer ' . $minutesRemaining . ' minuten opnieuw.', [
                'code' => null,
                'debug' => false,
            ]);
        }

        $allowed = $this->rateLimitService->recordAttempt(
            $requestRateLimitKey,
            self::RATE_LIMIT_SCOPE,
            false,
            self::MAX_CODES_PER_WINDOW,
            self::WINDOW_MINUTES,
            self::BLOCK_MINUTES
        );

        if (!$allowed) {
            $minutesRemaining = $this->rateLimitService->getBlockedMinutesRemaining($requestRateLimitKey, self::RATE_LIMIT_SCOPE);
            $logger->warning('OTP request rate-limited.', [
                'email' => $email,
                'blocked_minutes_remaining' => $minutesRemaining,
            ]);
            return $this->response(false, 'Je hebt te vaak een code aangevraagd. Probeer het over ongeveer ' . $minutesRemaining . ' minuten opnieuw.', [
                'code' => null,
                'debug' => false,
            ]);
        }

        $code = $this->generateCode();

        $mailSent = $this->sendCodeByEmail($email, $code);

        if (!$mailSent) {
            // In debug mode, allow the flow to continue even if email fails
            if (!$debugMode) {
                return $this->response(false, 'Kon verificatiecode niet versturen. Controleer de logs.', [
                    'code' => null,
                    'debug' => false,
                ]);
            }
            $logger->warning('OTP email failed in debug mode, continuing with local code.', [
                'email' => $email,
                'code' => $code,
            ]);
        }

        $this->repository->createCode($email, $code, self::EXPIRES_IN_MINUTES);
        $logger->info('OTP code generated and stored.', [
            'email' => $email,
            'expires_in_minutes' => self::EXPIRES_IN_MINUTES,
        ]);
        
        return $this->response(true, null, [
            'code' => $debugMode ? $code : null,
            'debug' => $debugMode,
        ]);
    }

    public function clearRequestAttempts(string $email): void
    {
        $this->rateLimitService->clearAttempts($this->getOtpRequestRateLimitKey($email), self::RATE_LIMIT_SCOPE);
    }

    /**
     * Check if code is valid.
     *
     * @param string $email The email address associated with the code
     * @param string $code The OTP code to validate
     * @return array|null The valid code data or null if invalid
     */
    public function validateCode(string $email, string $code): array
    {
        $email = strtolower(trim($email));
        $code = trim($code);
        $rateLimitKey = $this->getOtpAttemptRateLimitKey($email);

        if ($this->rateLimitService->isBlocked($rateLimitKey, self::VERIFY_RATE_LIMIT_SCOPE)) {
            $minutesRemaining = $this->rateLimitService->getBlockedMinutesRemaining($rateLimitKey, self::VERIFY_RATE_LIMIT_SCOPE);
            return $this->response(false, 'Je hebt te vaak een verificatiecode geprobeerd. Probeer het over ongeveer ' . $minutesRemaining . ' minuten opnieuw.', [
                'code' => null,
            ]);
        }

        $validCode = $this->repository->findValidCode($email, $code);

        if ($validCode === null) {
            $allowed = $this->rateLimitService->recordAttempt(
                $rateLimitKey,
                self::VERIFY_RATE_LIMIT_SCOPE,
                false,
                self::MAX_VERIFY_ATTEMPTS,
                self::VERIFY_WINDOW_MINUTES,
                self::VERIFY_BLOCK_MINUTES
            );

            if (!$allowed) {
                $minutesRemaining = $this->rateLimitService->getBlockedMinutesRemaining($rateLimitKey, self::VERIFY_RATE_LIMIT_SCOPE);
                return $this->response(false, 'Je hebt te vaak een verificatiecode geprobeerd. Probeer het over ongeveer ' . $minutesRemaining . ' minuten opnieuw.', [
                    'code' => null,
                ]);
            }

            return $this->response(false, 'Ongeldige of verlopen verificatiecode.', [
                'code' => null,
            ]);
        }

        $this->rateLimitService->clearAttempts($rateLimitKey, self::VERIFY_RATE_LIMIT_SCOPE);
        $this->repository->markCodeAsUsed($validCode['id']);

        return $this->response(true, null, [
            'code' => $validCode,
        ]);
    }

    private function getOtpRequestRateLimitKey(string $email): string
    {
        return $this->buildRateLimitKey($email, true);
    }

    private function getOtpAttemptRateLimitKey(string $email): string
    {
        return $this->buildRateLimitKey($email, false);
    }

    private function buildRateLimitKey(string $email, bool $includeClientIp): string
    {
        $normalizedEmail = strtolower(trim($email));
        $keyParts = [$normalizedEmail];

        if ($includeClientIp) {
            $keyParts[] = $this->getClientIp();
        }

        return hash('sha256', implode('|', $keyParts));
    }

    private function getClientIp(): string
    {
        return (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    }
    /**
     * Generates a new OTP code.
     *
     * @return string The generated OTP code
     */
    private function generateCode(): string
    {
        $code = '';
        for ($i = 0; $i < self::CODE_LENGTH; $i++) {
            $code .= random_int(0, 9);
        }
        return $code;
    }

    /**
     * Sends the OTP code via email. Maybe make email class in the future.
     *
     * @param string $email The email address to send the code to
     * @param string $code The OTP code to send
     * @return bool True if the email was sent successfully, false otherwise
     */
    private function sendCodeByEmail(string $email, string $code): bool
    {
        $logger = Logger::getInstance();
        $mail = new PHPMailer(true);

        try {
            // Als je lokaal wil testen met bijv mail trap uncomment dan deze code en vul de .env variabelen in. In productie worden deze instellingen genegeerd.
            // $mail->CharSet = 'UTF-8';
            // $mail->Encoding = 'base64';
            // $mail->isSMTP();
            // $mail->Host = getenv('MAIL_HOST');
            // $mail->Port = (int)getenv('MAIL_PORT');
            // $mail->SMTPAuth = true;
            // $mail->Username = getenv('MAIL_USERNAME');
            // $mail->Password = getenv('MAIL_PASSWORD');
            // $mail->SMTPSecure = getenv('MAIL_ENCRYPTION');
            // $mail->setFrom(getenv('MAIL_FROM') ?: 'noreply@jouwgids.nl', 'Beheer Jouw Gids');
            // $mail->addAddress($email);

            $mail->isHTML(true);
            // Als je lokaal test comment deze code en uncomment de bovenstaande code.
            $mail->setFrom('noreply@jouwgids.nl', 'Beheer Jouw Gids');
            $mail->addAddress($email);
            // tot hier
            $mail->Subject = 'Je verificatiecode is: ' . $code;

            $mail->Body = $this->getHTMLEmailBody($code);
            $mail->AltBody = $this->getPlainTextEmailBody($code);

            $mail->send();

            $logger->info('OTP email sent.', [
                'email' => $email,
            ]);

            return true;
        } catch (Exception $e) {
            $logger->error('OTP email failed.', [
                'email' => $email,
                'exception' => $e->getMessage(),
            ]);

            $this->lastError = 'Kon verificatiecode niet versturen.';
            return false;
        }
    }

    private function getHTMLEmailBody(string $code): string
    {
        return "
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
                .content { padding: 20px; background-color: #fff; border: 1px solid #ddd; border-radius: 5px; }
                .code-box { background-color: #e8f4f8; padding: 15px; border-left: 4px solid #0066cc; margin: 20px 0; font-size: 24px; font-weight: bold; letter-spacing: 3px; text-align: center; }
                .footer { margin-top: 20px; font-size: 12px; color: #666; text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Beheer Jouw Gids</h2>
                </div>
                <div class='content'>
                    <p>Hallo,</p>
                    <p>Hier is je verificatiecode om in te loggen:</p>
                    <div class='code-box'>$code</div>
                    <p>Deze code is 10 minuten geldig.</p>
                    <p>Als je dit verzoek niet hebt ingediend, kun je dit e-mailbericht negeren.</p>
                </div>
                <div class='footer'>
                    <p>Dit is een automatisch bericht, antwoord alstublieft niet op dit e-mailadres.</p>
                </div>
            </div>
        </body>
        </html>";
    }

    private function getPlainTextEmailBody(string $code): string
    {
        return "Beheer Jouw Gids\n\n" .
            "Hier is je verificatiecode om in te loggen:\n\n" .
            "$code\n\n" .
            "Deze code is 10 minuten geldig.\n\n" .
            "Als je dit verzoek niet hebt ingediend, kun je dit e-mailbericht negeren.\n\n" .
            "Dit is een automatisch bericht, antwoord alstublieft niet op dit e-mailadres.";
    }
}
