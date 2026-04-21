<?php

class UserRepository
{
    public function findActiveUserByEmail(string $email): ?array
    {
        $normalizedEmail = strtolower(trim($email));
        $result = execSQL(
            'SELECT UserID, First_name, Last_name, Email, Password, is_admin FROM gids_users WHERE LOWER(Email) = ? LIMIT 1',
            ['s', $normalizedEmail],
            false
        );

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        $row = $result->fetch_assoc();

        return [
            'id' => (int) ($row['UserID'] ?? 0),
            'first_name' => (string) ($row['First_name'] ?? ''),
            'last_name' => (string) ($row['Last_name'] ?? ''),
            'email' => (string) ($row['Email'] ?? ''),
            'password' => (string) ($row['Password'] ?? ''),
            'is_admin' => (int) ($row['is_admin'] ?? 0),
        ];
    }

    public function getUserById(int $userId): ?array
    {
        $result = execSQL(
            'SELECT UserID, First_name, Last_name, Email, is_admin FROM gids_users WHERE UserID = ? LIMIT 1',
            ['i', $userId],
            false
        );

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        $row = $result->fetch_assoc();

        return [
            'id' => (int) ($row['UserID'] ?? 0),
            'first_name' => (string) ($row['First_name'] ?? ''),
            'last_name' => (string) ($row['Last_name'] ?? ''),
            'email' => (string) ($row['Email'] ?? ''),
            'is_admin' => (int) ($row['is_admin'] ?? 0),
        ];
    }

    public function updatePassword(int $userId, string $passwordHash): void
    {
        execSQL(
            'UPDATE gids_users SET Password = ? WHERE UserID = ?',
            ['si', $passwordHash, $userId],
            true
        );
    }
}