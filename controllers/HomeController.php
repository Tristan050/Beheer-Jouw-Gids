<?php

class HomeController extends BaseController
{
    public function index(): void
    {
        $this->render('home/home');
    }
}
