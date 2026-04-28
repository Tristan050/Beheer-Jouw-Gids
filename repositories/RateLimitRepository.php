<?php

class RateLimitRepository
{
    public function findBlockedUntil(string $key, string $scope): ?string
    {
        $result = execSQL(
            'SELECT blocked_until FROM gids_rate_limits 
             WHERE rate_key = ? AND scope = ? 
             ORDER BY blocked_until DESC LIMIT 1',
            ['ss', $key, $scope],
            false
        );

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        $row = $result->fetch_assoc();
        return $row['blocked_until'] ?? null;
    }

    public function deleteOldAttempts(string $key, string $scope, string $windowStart): void
    {
        execSQL(
            'DELETE FROM gids_rate_limits 
             WHERE rate_key = ? AND scope = ? AND window_start < ?',
            ['sss', $key, $scope, $windowStart],
            true
        );
    }

    public function deleteAllAttempts(string $key, string $scope): void
    {
        execSQL(
            'DELETE FROM gids_rate_limits WHERE rate_key = ? AND scope = ?',
            ['ss', $key, $scope],
            true
        );
    }

    public function countAttempts(string $key, string $scope, string $windowStart): int
    {
        $result = execSQL(
            'SELECT attempts FROM gids_rate_limits 
             WHERE rate_key = ? AND scope = ? AND window_start >= ?',
            ['sss', $key, $scope, $windowStart],
            false
        );

        if (!$result || $result->num_rows === 0) {
            return 0;
        }

        $row = $result->fetch_assoc();
        return (int) ($row['attempts'] ?? 0);
    }

    public function createAttempt(string $key, string $scope, string $now, ?string $blockedUntil): void
    {
        execSQL(
            'INSERT INTO gids_rate_limits (rate_key, scope, attempts, window_start, blocked_until) 
             VALUES (?, ?, 1, ?, ?)
             ON DUPLICATE KEY UPDATE
                attempts = attempts + 1,
                blocked_until = VALUES(blocked_until)',
            ['ssss', $key, $scope, $now, $blockedUntil],
            true
        );
    }
}
