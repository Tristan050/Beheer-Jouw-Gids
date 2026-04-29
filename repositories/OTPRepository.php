<?php

class OTPRepository
{
    /**
     * Creates a new OTP code for the email given with an expiration.
     * @param string $email The email adress to send the code to.
     * @param string $code the OTP/code.
     * @param int $expiresInMinutes how long the code is valid for. Default is 10 minutes. (this is fallback)
     */
    public function createCode(string $email, string $code, int $expiresInMinutes = 10): void
    {
        $expiresAt = (new DateTime())->add(new DateInterval('PT' . $expiresInMinutes . 'M'));
        
        execSQL(
            'INSERT INTO gids_otp_codes (email, code, expires_at) VALUES (?, ?, ?)',
            ['sss', $email, $code, $expiresAt->format('Y-m-d H:i:s')],
            true
        );
    }
    /**
     * Finds the OTP code associated with the email that isn't used and isn't expired. Returns null if no valid code is found.
      * @param string $email The email adress to check the code for.
      * @param string $code the OTP/code to validate.
      * @return array|null An associative array with code details or null if not valid.
     */
    public function findValidCode(string $email, string $code): ?array
    {
        $result = execSQL(
            'SELECT id, email, code, created_at, expires_at, used_at 
             FROM gids_otp_codes 
             WHERE email = ? AND code = ? AND used_at IS NULL AND expires_at > NOW()
             ORDER BY created_at DESC LIMIT 1',
            ['ss', $email, $code],
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
            'created_at' => (string) ($row['created_at'] ?? ''),
            'expires_at' => (string) ($row['expires_at'] ?? ''),
            'used_at' => $row['used_at'] ?? null,
        ];
    }
    /**
     * Marks a code as used by setting the used_at timestamp to now.
      * @param int $codeId The id of the code.
     */
    public function markCodeAsUsed(int $codeId): void
    {
        execSQL(
            'UPDATE gids_otp_codes SET used_at = NOW() WHERE id = ?',
            ['i', $codeId],
            true
        );
    }
    /**
     * Counts how many codes are there for the email in a timeframe to prevent abuse.
      * @param string $email The email adress to check the code for.
      * @param int $minutesWindow The timeframe in minutes to look back for code creation. Default is 10 minutes.
      * @return int The count of codes created in the timeframe.
     */
    public function countRecentCodes(string $email, int $minutesWindow = 10): int
    {
        $windowStart = (new DateTime())->sub(new DateInterval('PT' . $minutesWindow . 'M'));
        
        $result = execSQL(
            'SELECT COUNT(*) as code_count FROM gids_otp_codes 
             WHERE email = ? AND created_at >= ?',
            ['ss', $email, $windowStart->format('Y-m-d H:i:s')],
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
        return (int) execSQL(
            'DELETE FROM gids_otp_codes WHERE expires_at < NOW()',
            [],
            true
        );
    }
}
