<?php

class UserRepository
{
    public function findActiveUserByEmail(string $email): ?array
    {
        $normalizedEmail = strtolower(trim($email));
        $result = execSQL(
            'SELECT UserID, First_name, Last_name, Email, Password, is_admin, is_verified FROM gids_users WHERE LOWER(Email) = ? LIMIT 1',
            ['s', $normalizedEmail],
            false
        );

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        $row = $result->fetch_assoc();
        $userId = (int) ($row['UserID'] ?? 0);
        $roles = $this->getUserRoles($userId);

        return [
            'id' => $userId,
            'first_name' => (string) ($row['First_name'] ?? ''),
            'last_name' => (string) ($row['Last_name'] ?? ''),
            'email' => (string) ($row['Email'] ?? ''),
            'password' => (string) ($row['Password'] ?? ''),
            'is_admin' => (int) ($row['is_admin'] ?? 0),
            'is_verified' => (int) ($row['is_verified'] ?? 0),
            'roles' => $roles,
        ];
    }

    public function getUserById(int $userId): ?array
    {
        $result = execSQL(
            'SELECT UserID, First_name, Last_name, Email, is_admin, is_verified FROM gids_users WHERE UserID = ? LIMIT 1',
            ['i', $userId],
            false
        );

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        $row = $result->fetch_assoc();
        $roles = $this->getUserRoles($userId);

        return [
            'id' => (int) ($row['UserID'] ?? 0),
            'first_name' => (string) ($row['First_name'] ?? ''),
            'last_name' => (string) ($row['Last_name'] ?? ''),
            'email' => (string) ($row['Email'] ?? ''),
            'is_admin' => (int) ($row['is_admin'] ?? 0),
            'is_verified' => (int) ($row['is_verified'] ?? 0),
            'roles' => $roles,
        ];
    }

    public function getUserRoles(int $userId): array
    {
        $result = execSQL(
            'SELECT r.name FROM gids_user_role ur
             INNER JOIN gids_role r ON ur.role_id = r.id
             WHERE ur.user_id = ?
             ORDER BY r.name',
            ['i', $userId],
            false
        );

        if (!$result || $result->num_rows === 0) {
            return [];
        }

        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = (string) ($row['name'] ?? '');
        }

        return $roles;
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