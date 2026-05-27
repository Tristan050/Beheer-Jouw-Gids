<?php

class AuthService extends BaseService
{
    private const LOGIN_RATE_LIMIT_SCOPE = 'login_attempt';

    public function __construct(
        private readonly UserRepository $userRepository = new UserRepository(),
        private readonly RateLimitService $rateLimitService = new RateLimitService()
    ) {
    }

    public function attemptLogin(string $email, string $password): mixed
    {
        $rateLimitKey = $this->getLoginRateLimitKey($email);

        if ($this->rateLimitService->isBlocked($rateLimitKey, self::LOGIN_RATE_LIMIT_SCOPE)) {
            $minutesRemaining = $this->rateLimitService->getBlockedMinutesRemaining($rateLimitKey, self::LOGIN_RATE_LIMIT_SCOPE);
            return $this->response(false, "Te veel inlogpogingen. Probeer over $minutesRemaining minuten opnieuw.", [
                'remaining_attempts' => 0,
                'blocked_minutes' => $minutesRemaining,
            ]);
        }

        $user = $this->userRepository->findActiveUserByEmail($email);

        if ($user === null) {
            $this->rateLimitService->recordAttempt($rateLimitKey, self::LOGIN_RATE_LIMIT_SCOPE, false);
            $remainingAttempts = $this->rateLimitService->getRemainingAttempts($rateLimitKey, self::LOGIN_RATE_LIMIT_SCOPE);

            return $this->response(false, 'Email of wachtwoord onjuist.', [
                'remaining_attempts' => $remainingAttempts,
            ]);
        }

        $passwordHash = (string) ($user['password'] ?? '');

        if ($passwordHash === '' || !password_verify($password, $passwordHash)) {
            $this->rateLimitService->recordAttempt($rateLimitKey, self::LOGIN_RATE_LIMIT_SCOPE, false);
            $remainingAttempts = $this->rateLimitService->getRemainingAttempts($rateLimitKey, self::LOGIN_RATE_LIMIT_SCOPE);

            return $this->response(false, 'Email of wachtwoord onjuist.', [
                'remaining_attempts' => $remainingAttempts,
            ]);
        }

        if (password_needs_rehash($passwordHash, PASSWORD_DEFAULT)) {
            $this->userRepository->updatePassword((int) $user['id'], password_hash($password, PASSWORD_DEFAULT));
        }

        $userRoles = $user['roles'] ?? [];
        if (!array_intersect($userRoles, ['super_admin', 'admin'])) {
            $this->rateLimitService->clearAttempts($rateLimitKey, self::LOGIN_RATE_LIMIT_SCOPE);
            return $this->response(false, 'Je hebt geen toegang tot dit admin-paneel.', [
                'remaining_attempts' => 0,
            ]);
        }

        $this->rateLimitService->clearAttempts($rateLimitKey, self::LOGIN_RATE_LIMIT_SCOPE);

        return $this->response(true, null, [
            'user' => $user,
        ]);
    }

    private function getLoginRateLimitKey(string $email): string
    {
        $normalizedEmail = strtolower(trim($email));
        $clientIp = $this->getClientIp();

        return hash('sha256', $normalizedEmail . '|' . $clientIp);
    }

    private function getClientIp(): string
    {
        return (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    }

    public function getAuthenticatedUser(): ?array
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }

        return $this->userRepository->getUserById((int) $_SESSION['user_id']);
    }

    public function logout(): void
    {
        unset($_SESSION['user_id'], $_SESSION['user_roles'], $_SESSION['user_verified'], $_SESSION['user']);
        session_regenerate_id(true);
    }

    public function hasRole(string $role): bool
    {
        if (empty($_SESSION['user_id'])) {
            return false;
        }

        $userRoles = $_SESSION['user_roles'] ?? [];
        return in_array($role, $userRoles, true);
    }

    public static function hasAnyRole(array $roles): bool
    {
        if (!isLoggedIn()) {
            return false;
        }

        $userRoles = $_SESSION['user_roles'] ?? [];
        foreach ($roles as $role) {
            if (in_array($role, $userRoles, true)) {
                return true;
            }
        }

        return false;
    }

    public function isUserVerified(): bool
    {
        return (bool) ($_SESSION['user_verified'] ?? false);
    }
}
