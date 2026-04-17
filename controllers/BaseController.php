<?php 

class BaseController
{
    protected function render(string $viewPath, array $data = []): void
    {
        $viewData = $data;
        require_once __DIR__ . '/../views/' . $viewPath . '.view.php';
    }
}