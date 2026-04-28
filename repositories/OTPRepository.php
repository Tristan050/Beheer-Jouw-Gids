<?php

class OTPRepository
{
    public function createCode(string $email, string $code, int $expiresInMinutes = 10): void
    {
        $expiresAt = (new DateTime())->add(new DateInterval('PT' . $expiresInMinutes . 'M'));
        
        execSQL(
            'INSERT INTO gids_otp_codes (email, code, expires_at) VALUES (?, ?, ?)',
            ['sss', $email, $code, $expiresAt->format('Y-m-d H:i:s')],
            true
        );
    }

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

    public function markCodeAsUsed(int $codeId): void
    {
        execSQL(
            'UPDATE gids_otp_codes SET used_at = NOW() WHERE id = ?',
            ['i', $codeId],
            true
        );
    }

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

    public function deleteExpiredCodes(): int
    {
        return (int) execSQL(
            'DELETE FROM gids_otp_codes WHERE expires_at < NOW()',
            [],
            true
        );
    }
}
