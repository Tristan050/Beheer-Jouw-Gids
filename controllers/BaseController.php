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
}