<?php

class OTPRepository
{
    private const DATETIME_FORMAT = 'Y-m-d H:i:s';

    private function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    private function formatDateTime(DateTimeInterface $dateTime): string
    {
        return $dateTime->setTimezone(new DateTimeZone('UTC'))->format(self::DATETIME_FORMAT);
    }

    /**
     * Creates a new OTP code for the email given with an expiration.
     * @param string $email The email address to send the code to.
     * @param string $code the OTP/code.
     * @param int $expiresInMinutes how long the code is valid for. Default is 10 minutes. (this is fallback)
     */
    public function createCode(string $email, string $code, int $expiresInMinutes = 10): void
    {
        $now = $this->now();
        $createdAt = $this->formatDateTime($now);
        $expiresAt = $this->formatDateTime($now->modify('+' . $expiresInMinutes . ' minutes'));
        
        execSQL(
            'INSERT INTO gids_otp_codes (email, code, created_at, expires_at) VALUES (?, ?, ?, ?)',
            ['ssss', $email, $code, $createdAt, $expiresAt],
            true
        );
    }
    /**
     * Finds the OTP code associated with the email that isn't used and isn't expired. Returns null if no valid code is found.
      * @param string $email The email address to check the code for.
      * @param string $code the OTP/code to validate.
      * @return array|null An associative array with code details or null if not valid.
     */
    public function findValidCode(string $email, string $code): ?array
    {
        $now = $this->formatDateTime($this->now());

        $result = execSQL(
            'SELECT id, email, code, created_at, expires_at, used_at 
             FROM gids_otp_codes 
             WHERE email = ? AND code = ? AND used_at IS NULL AND expires_at > ?
             ORDER BY created_at DESC LIMIT 1',
            ['sss', $email, $code, $now],
            false
        );

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        $row = $result->fetch_assoc();
        return [
            'id' => (int) ($row['id'] ?? 0),
            'email' => (string) ($row['email'] ?? ''),
            'code' => (string) ($row['code'] ?? ''),
            'created_at' => $row['created_at'] ?? null,
            'expires_at' => $row['expires_at'] ?? null,
            'used_at' => $row['used_at'] ?? null,
        ];
    }
    /**
     * Marks a code as used by setting the used_at timestamp to now.
      * @param int $codeId The id of the code.
     */
    public function markCodeAsUsed(int $codeId): void
    {
        $now = $this->formatDateTime($this->now());

        execSQL(
            'UPDATE gids_otp_codes SET used_at = ? WHERE id = ?',
            ['si', $now, $codeId],
            true
        );
    }
    /**
     * Counts how many codes are there for the email in a timeframe to prevent abuse.
            * @param string $email The email address to check the code for.
      * @param int $minutesWindow The timeframe in minutes to look back for code creation. Default is 10 minutes.
      * @return int The count of codes created in the timeframe.
     */
    public function countRecentCodes(string $email, int $minutesWindow = 10): int
    {
        $windowStart = $this->formatDateTime($this->now()->modify('-' . $minutesWindow . ' minutes'));
        
        $result = execSQL(
            'SELECT COUNT(*) as code_count FROM gids_otp_codes 
             WHERE email = ? AND created_at >= ?',
            ['ss', $email, $windowStart],
            false
        );

        if (!$result || $result->num_rows === 0) {
            return 0;
        }

        $row = $result->fetch_assoc();
        return (int) ($row['code_count'] ?? 0);
    }
    /** Deletes all expired codes from the database. Returns the number of deleted codes.
     * @return int The number of deleted expired codes.
     */
    public function deleteExpiredCodes(): int
    {
        $now = $this->formatDateTime($this->now());

        return (int) execSQL(
            'DELETE FROM gids_otp_codes WHERE expires_at < ?',
            ['s', $now],
            true
        );
    }
}
