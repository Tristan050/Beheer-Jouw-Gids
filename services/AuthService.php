<?php

class AuthService
{
    public function __construct(private readonly UserRepository $userRepository = new UserRepository())
    {
    }

    public function attemptLogin(string $email, string $password): bool
    {
        $user = $this->userRepository->findActiveUserByEmail($email);

        if ($user === null) {
            return false;
        }

        $passwordHash = (string) ($user['password'] ?? '');

        if ($passwordHash === '' || !password_verify($password, $passwordHash)) {
            return false;
        }

        if (password_needs_rehash($passwordHash, PASSWORD_DEFAULT)) {
            $this->userRepository->updatePassword((int) $user['id'], password_hash($password, PASSWORD_DEFAULT));
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = (int) $user['id'];

        return true;
    }

    public function getAuthenticatedUser(): ?array
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }

        return $this->userRepository->getUserById((int) $_SESSION['user_id']);
    }

    public function logout(): void
    {
        unset($_SESSION['user_id'], $_SESSION['user']);
        session_regenerate_id(true);
    }
}