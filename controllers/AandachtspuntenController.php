<?php

class AandachtspuntenController extends BaseController
{
    public function index(): void
    {
        $this->render('admin/aandachtspunten');
    }

    public function edit(): void
    {
        $this->render('admin/aandachtspunt-edit');
    }
}
