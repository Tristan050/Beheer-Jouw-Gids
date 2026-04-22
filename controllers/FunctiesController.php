<?php

class FunctiesController extends BaseController
{
    public function index(): void
    {
        $this->render('admin/functies');
    }

    public function edit(): void
    {
        $this->render('admin/functie-edit');
    }
}
