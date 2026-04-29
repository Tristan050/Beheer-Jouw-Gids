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
        $this->requireSuperAdmin();

        $user = $this->authService->getAuthenticatedUser();

        $this->render('admin/admin', [
            'user' => $user ?? [],
        ]);
    }
}
