<?php

class HomeController extends BaseController
{
    private AuthService $authService;

    public function __construct(?AuthService $authService = null)
    {
        $this->authService = $authService ?? new AuthService();
    }

    public function index(): void
    {
        if (!isLoggedIn()) {
            setFlash('auth_error', 'Log eerst in om verder te gaan.');
            redirect(appUrl('login'));
        }

        $user = $this->authService->getAuthenticatedUser();

        $this->render('admin/admin', [
            'user' => $user ?? [],
        ]);
    }
}
