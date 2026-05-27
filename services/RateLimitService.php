<?php

class RateLimitService
{
    private const DATETIME_FORMAT = 'Y-m-d H:i:s';
    private const MAX_ATTEMPTS = 5;
    private const WINDOW_MINUTES = 15;
    private const BLOCK_MINUTES = 30;

    public function __construct(private readonly RateLimitRepository $repository = new RateLimitRepository())
    {
    }

    private function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    private function formatDateTime(DateTimeInterface $dateTime): string
    {
        return $dateTime->setTimezone(new DateTimeZone('UTC'))->format(self::DATETIME_FORMAT);
    }

    private function parseDateTime(?string $dateTime): ?DateTimeImmutable
    {
        if ($dateTime === null || $dateTime === '') {
            return null;
        }

        $parsedDateTime = DateTimeImmutable::createFromFormat(self::DATETIME_FORMAT, $dateTime, new DateTimeZone('UTC'));
        return $parsedDateTime ?: null;
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
        $blockedUntilTime = $this->parseDateTime($blockedUntil);

        if ($blockedUntilTime === null) {
            return false;
        }

        return $this->now() < $blockedUntilTime;
    }

    /**
     * Records a login attempt if attempt is within the rate limit.
     *
     * @param string $key The key to record the attempt for (e.g. email)
     * @param string $scope The scope of the rate limit (default: 'admin_login')
     * @param bool $isSuccess Whether the login attempt was successful
     * @return bool True if the attempt was recorded successfully, false otherwise
     */
    public function recordAttempt(
        string $key,
        string $scope = 'admin_login',
        bool $isSuccess = false,
        ?int $maxAttempts = null,
        ?int $windowMinutes = null,
        ?int $blockMinutes = null
    ): bool
    {
        $maxAttempts ??= self::MAX_ATTEMPTS;
        $windowMinutes ??= self::WINDOW_MINUTES;
        $blockMinutes ??= self::BLOCK_MINUTES;

        $now = $this->now();
        $windowStart = $now->modify('-' . $windowMinutes . ' minutes');

        $this->repository->deleteOldAttempts($key, $scope, $this->formatDateTime($windowStart));

        if ($isSuccess) {
            $this->repository->deleteAllAttempts($key, $scope);
            return true;
        }

        $attemptCount = $this->repository->countAttempts($key, $scope, $this->formatDateTime($windowStart));

        $blockedUntil = null;
        if ($attemptCount >= $maxAttempts) {
            $blockedUntil = $this->formatDateTime($now->modify('+' . $blockMinutes . ' minutes'));
        }

        $this->repository->createAttempt($key, $scope, $this->formatDateTime($now), $blockedUntil);

        return $attemptCount < $maxAttempts;
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

        $windowStart = $this->now()->modify('-' . self::WINDOW_MINUTES . ' minutes');

        $attemptCount = $this->repository->countAttempts($key, $scope, $this->formatDateTime($windowStart));

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

        $blockTime = $this->parseDateTime($blockedUntil);
        if ($blockTime === null) {
            return 0;
        }

        $diff = $blockTime->diff($this->now());

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
