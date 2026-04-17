<?php

class AuthController extends BaseController
{
    public function index(): void
    {
        $this->render('auth/login');
    }
}
