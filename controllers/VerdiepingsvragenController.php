<?php

class VerdiepingsvragenController extends BaseController
{
    public function index(): void
    {
        $this->render('admin/verdiepingsvragen');
    }

    public function edit(): void
    {
        $this->render('admin/verdieping-vraag-edit');
    }
}
