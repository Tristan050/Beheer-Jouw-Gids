<?php

class LeefgebiedenController extends BaseController
{
    public function index(): void
    {
        $this->render('admin/leefgebieden');
    }

    public function edit(): void
    {
        $this->render('admin/leefgebied-edit');
    }
}
