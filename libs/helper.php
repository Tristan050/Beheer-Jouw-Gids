<?php

function isLoggedin(): bool
{
    return !empty($_SESSION['user_id']);
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}
