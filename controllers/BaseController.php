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

    protected function requireRole(string $role): void
    {
        if (!isLoggedIn()) {
            setFlash('auth_error', 'Log eerst in om verder te gaan.');
            redirect(appUrl('login'));
        }

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
        if (!isLoggedIn()) {
            setFlash('auth_error', 'Log eerst in om verder te gaan.');
            redirect(appUrl('login'));
        }

        if (!hasAnyRole(['super_admin', 'admin'])) {
            setFlash('auth_error', 'Je hebt geen toestemming om deze pagina te bezoeken.');
            redirect(appUrl(''));
        }
    }
}