<?php

class BaseController
{
    protected function render(string $viewPath, array $data = []): void
    {
        require_once __DIR__ . '/../views/' . $viewPath . '.view.php';
    }

    protected function auth(): void
    {
        if (!isLoggedIn()) {
            setFlash('auth_error', 'Log eerst in om verder te gaan.');
            redirect(appUrl('login'));
        }
    }

    protected function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            throw new HttpException(405, 'Methode niet toegestaan');
        }
    }

    protected function requireRole(string $role): void
    {
        self::auth();

        if (!hasRole($role)) {
            setFlash('auth_error', 'Je hebt geen toestemming om deze pagina te bezoeken.');
            redirect(appUrl(''));
        }
    }

    protected function requireSuperAdmin(): void
    {
        $this->requireRole('super_admin');
    }

    protected function requireAdmin(): void
    {
        self::auth();

        if (!hasAnyRole(['super_admin', 'admin'])) {
            setFlash('auth_error', 'Je hebt geen toestemming om deze pagina te bezoeken.');
            redirect(appUrl(''));
        }
    }
}