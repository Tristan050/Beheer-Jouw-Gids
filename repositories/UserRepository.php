<?php

class UserRepository
{
    public function findActiveUserByEmail(string $email): ?array
    {
        $normalizedEmail = strtolower(trim($email));
        $result = execSQL(
            'SELECT user_id, first_name, last_name, email, password, is_admin, is_verified FROM gids_users WHERE LOWER(email) = ? LIMIT 1',
            ['s', $normalizedEmail],
            false
        );

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        $row = $result->fetch_assoc();
        $userId = (int) ($row['user_id'] ?? 0);
        $roles = $this->getUserRoles($userId);

        return [
            'id' => $userId,
            'first_name' => (string) ($row['first_name'] ?? ''),
            'last_name' => (string) ($row['last_name'] ?? ''),
            'email' => (string) ($row['email'] ?? ''),
            'password' => (string) ($row['password'] ?? ''),
            'is_admin' => (int) ($row['is_admin'] ?? 0),
            'is_verified' => (int) ($row['is_verified'] ?? 0),
            'roles' => $roles,
        ];
    }

    public function getUserById(int $userId): ?array
    {
        $result = execSQL(
            'SELECT user_id, first_name, last_name, email, is_admin, is_verified FROM gids_users WHERE user_id = ? LIMIT 1',
            ['i', $userId],
            false
        );

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        $row = $result->fetch_assoc();
        $roles = $this->getUserRoles($userId);

        return [
            'id' => (int) ($row['user_id'] ?? 0),
            'first_name' => (string) ($row['first_name'] ?? ''),
            'last_name' => (string) ($row['last_name'] ?? ''),
            'email' => (string) ($row['email'] ?? ''),
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
            'UPDATE gids_users SET password = ? WHERE user_id = ?',
            ['si', $passwordHash, $userId],
            true
        );
    }
}