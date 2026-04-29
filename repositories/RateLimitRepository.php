<?php

class RateLimitRepository
{
    /**
     * Checks if the key and scope are currently blocked and returns a timestamp if it is.
     * @param string $key The key to check.
     * @param string $scope The scope of the rate limit (e.g. "login").
     * @return string|null The timestamp until which the key is blocked, or null if not.
     */
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
    /** Deletes old attempts that aren't in use anymore.
     * @param string $key The key to delete attempts for.
     * @param string $scope The scope of the rate limit  (e.g. "login").
     * @param string $windowStart The start of the current window in Y-m-d H:i:s format.
     */
    public function deleteOldAttempts(string $key, string $scope, string $windowStart): void
    {
        execSQL(
            'DELETE FROM gids_rate_limits 
             WHERE rate_key = ? AND scope = ? AND window_start < ?',
            ['sss', $key, $scope, $windowStart],
            true
        );
    }
    /** Deletes all attempts for a key and scope, used after a successful login or when blocking a key.
     * @param string $key The key to delete attempts for.
     * @param string $scope The scope of the rate limit (e.g. "login").
     */
    public function deleteAllAttempts(string $key, string $scope): void
    {
        execSQL(
            'DELETE FROM gids_rate_limits WHERE rate_key = ? AND scope = ?',
            ['ss', $key, $scope],
            true
        );
    }
    /**
     * Counts the number of attempts for a key and scope within the current timestamp window.
     * @param string $key The key to count attempts for.
     * @param string $scope The scope of the rate limit (e.g. "login").
     * @param string $windowStart The start of the current window in Y-m-d H:i:s format.
     * @return int The number of attempts in the current window.
     */
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
    /**
     * Creates a new attempt for a key and scope it will increment the attempt count on duplicate.
     * @param string $key The key to create an attempt for.
     * @param string $scope The scope of the rate limit (e.g. "login").
     * @param string $now The current timestamp in Y-m-d H:i:s format.
     * @param string|null $blockedUntil The timestamp until which the key should be blocked in or null for no block.
     */
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
