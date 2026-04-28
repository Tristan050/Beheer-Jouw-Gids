<?php

class RateLimitService
{
    private const MAX_ATTEMPTS = 5;
    private const WINDOW_MINUTES = 15;
    private const BLOCK_MINUTES = 30;

    public function __construct(private readonly RateLimitRepository $repository = new RateLimitRepository())
    {
    }

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

    public function clearAttempts(string $key, string $scope = 'admin_login'): void
    {
        $this->repository->deleteAllAttempts($key, $scope);
    }
}
