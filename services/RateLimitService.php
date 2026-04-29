<?php

class RateLimitService
{
    private const MAX_ATTEMPTS = 5;
    private const WINDOW_MINUTES = 15;
    private const BLOCK_MINUTES = 30;

    public function __construct(private readonly RateLimitRepository $repository = new RateLimitRepository())
    {
    }
    /**
     * Checks if the given key is currently blocked for the specified scope.
     *
     * @param string $key The key to check (e.g. email)
     * @param string $scope The scope of the rate limit (default: 'admin_login')
     * @return bool True if the key is blocked, false otherwise
     */

    public function isBlocked(string $key, string $scope = 'admin_login'): bool
    {
        $blockedUntil = $this->repository->findBlockedUntil($key, $scope);

        if ($blockedUntil === null) {
            return false;
        }

        $now = new DateTime();
        $blockTime = DateTime::createFromFormat('Y-m-d H:i:s', $blockedUntil);

        return $now < $blockTime;
    }

    /**
     * Records a login attempt if attempt is within the rate limit.
     *
     * @param string $key The key to record the attempt for (e.g. email)
     * @param string $scope The scope of the rate limit (default: 'admin_login')
     * @param bool $isSuccess Whether the login attempt was successful
     * @return bool True if the attempt was recorded successfully, false otherwise
     */
    public function recordAttempt(string $key, string $scope = 'admin_login', bool $isSuccess = false): bool
    {
        $now = new DateTime();
        $windowStart = (clone $now)->sub(new DateInterval('PT' . self::WINDOW_MINUTES . 'M'));

        $this->repository->deleteOldAttempts($key, $scope, $windowStart->format('Y-m-d H:i:s'));

        if ($isSuccess) {
            $this->repository->deleteAllAttempts($key, $scope);
            return true;
        }

        $attemptCount = $this->repository->countAttempts($key, $scope, $windowStart->format('Y-m-d H:i:s'));

        $blockedUntil = null;
        if ($attemptCount >= self::MAX_ATTEMPTS) {
            $blockedUntil = (clone $now)->add(new DateInterval('PT' . self::BLOCK_MINUTES . 'M'))->format('Y-m-d H:i:s');
        }

        $this->repository->createAttempt($key, $scope, $now->format('Y-m-d H:i:s'), $blockedUntil);

        return $attemptCount < self::MAX_ATTEMPTS;
    }

    /**
     * Gets the number of remaining attempts before the key is blocked.
     *
     * @param string $key The key to check (e.g. email)
     * @param string $scope The scope of the rate limit (default: 'admin_login')
     * @return int The number of remaining attempts before blocking
     */
    public function getRemainingAttempts(string $key, string $scope = 'admin_login'): int
    {
        if ($this->isBlocked($key, $scope)) {
            return 0;
        }

        $now = new DateTime();
        $windowStart = (clone $now)->sub(new DateInterval('PT' . self::WINDOW_MINUTES . 'M'));

        $attemptCount = $this->repository->countAttempts($key, $scope, $windowStart->format('Y-m-d H:i:s'));

        $remaining = max(0, self::MAX_ATTEMPTS - $attemptCount);
        return $remaining;
    }
    /**
     * Gets the number of minutes remaining before the key is unblocked.
     *
     * @param string $key The key to check (e.g. email)
     * @param string $scope The scope of the rate limit (default: 'admin_login')
     * @return int The number of minutes remaining before unblocking
     */
    public function getBlockedMinutesRemaining(string $key, string $scope = 'admin_login'): int
    {
        if (!$this->isBlocked($key, $scope)) {
            return 0;
        }

        $blockedUntil = $this->repository->findBlockedUntil($key, $scope);
        if ($blockedUntil === null) {
            return 0;
        }

        $now = new DateTime();
        $blockTime = DateTime::createFromFormat('Y-m-d H:i:s', $blockedUntil);
        $diff = $blockTime->diff($now);

        return (int) ($diff->days * 24 * 60 + $diff->h * 60 + $diff->i) + 1;
    }
    /**
     * Clears all attempts for the given key and scope, effectively unblocking it.
     *
     * @param string $key The key to clear attempts for (e.g. email)
     * @param string $scope The scope of the rate limit (default: 'admin_login')
     */
    public function clearAttempts(string $key, string $scope = 'admin_login'): void
    {
        $this->repository->deleteAllAttempts($key, $scope);
    }
}
