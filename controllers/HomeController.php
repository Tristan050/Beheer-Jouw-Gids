<?php

class HomeController extends BaseController
{
    public function admin(): void
    {
        $this->render('admin');
    }

    public function index(): void
    {
        $this->render('home/home');
    }
}
