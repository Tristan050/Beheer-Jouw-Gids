<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class OTPService
{
    private const MAX_CODES_PER_WINDOW = 5;
    private const WINDOW_MINUTES = 10;
    private const CODE_LENGTH = 6;
    private const EXPIRES_IN_MINUTES = 10;

    private ?string $lastError = null;

    public function __construct(private readonly OTPRepository $repository = new OTPRepository()) {}

    public function generateAndSendCode(string $email): bool
    {
        $logger = Logger::getInstance();
        $this->lastError = null;
        $email = strtolower(trim($email));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $logger->warning('OTP request rejected for invalid email.', [
                'email' => $email,
            ]);
            $this->lastError = 'Vul een geldig e-mailadres in.';
            return false;
        }

        $recentCodeCount = $this->repository->countRecentCodes($email, self::WINDOW_MINUTES);
        if ($recentCodeCount >= self::MAX_CODES_PER_WINDOW) {
            $logger->warning('OTP request rate-limited.', [
                'email' => $email,
                'recent_code_count' => $recentCodeCount,
                'window_minutes' => self::WINDOW_MINUTES,
                'max_codes' => self::MAX_CODES_PER_WINDOW,
            ]);
            $this->lastError = 'Je hebt te vaak een code aangevraagd. Probeer het over ongeveer 10 minuten opnieuw.';
            return false;
        }

        $code = $this->generateCode();

        $mailSent = $this->sendCodeByEmail($email, $code);

        if (!$mailSent) {
            $this->lastError = 'Kon verificatiecode niet versturen. Controleer de logs.';
            return false;
        }

        $this->repository->createCode($email, $code, self::EXPIRES_IN_MINUTES);
        $logger->info('OTP code generated and stored.', [
            'email' => $email,
            'expires_in_minutes' => self::EXPIRES_IN_MINUTES,
        ]);
        return true;
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function validateCode(string $email, string $code): ?array
    {
        $email = strtolower(trim($email));
        $code = trim($code);

        $validCode = $this->repository->findValidCode($email, $code);

        if ($validCode === null) {
            return null;
        }

        $this->repository->markCodeAsUsed($validCode['id']);

        return $validCode;
    }

    private function generateCode(): string
    {
        $code = '';
        for ($i = 0; $i < self::CODE_LENGTH; $i++) {
            $code .= random_int(0, 9);
        }
        return $code;
    }

    private function sendCodeByEmail(string $email, string $code): bool
    {
        $logger = Logger::getInstance();
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = getenv('MAIL_HOST');
            $mail->Port = (int)getenv('MAIL_PORT');
            $mail->SMTPAuth = true;
            $mail->Username = getenv('MAIL_USERNAME');
            $mail->Password = getenv('MAIL_PASSWORD');
            $mail->SMTPSecure = getenv('MAIL_ENCRYPTION');
            $mail->setFrom(getenv('MAIL_FROM') ?: 'noreply@jouwgids.nl', 'Beheer Jouw Gids');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Je verificatiecode is: ' . $code;

            $htmlBody = $this->getHTMLEmailBody($code);
            $mail->Body = $htmlBody;
            $mail->AltBody = $this->getPlainTextEmailBody($code);

            $mail->send();
            $logger->info('OTP email sent.', [
                'email' => $email,
            ]);
            return true;
        } catch (PHPMailerException $e) {
            $logger->error('OTP email failed.', [
                'email' => $email,
                'exception' => $e->getMessage(),
            ]);
            $this->lastError = 'Kon verificatiecode niet versturen. Controleer de logs.';
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
