<?php

function isLoggedIn(): bool
{
    return !empty($_SESSION['user_id']);
}

function appUrl(string $path = ''): string
{
    $baseUrl = defined('APP_BASE_URL') ? rtrim((string) APP_BASE_URL, '/') : '';
    $path = ltrim($path, '/');

    if ($baseUrl === '') {
        return '/' . $path;
    }

    return $path === '' ? $baseUrl : $baseUrl . '/' . $path;
}

function getFlash(string $key): ?string
{
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }

    $message = (string) $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);

    if (empty($_SESSION['flash'])) {
        unset($_SESSION['flash']);
    }

    return $message;
}

function setFlash(string $key, string $value): void
{
    $_SESSION['flash'][$key] = $value;
}

function rememberInput(array $values): void
{
    $_SESSION['old_input'] = $values;
}

function old(string $key, string $default = ''): string
{
    return isset($_SESSION['old_input'][$key]) ? (string) $_SESSION['old_input'][$key] : $default;
}

function clearOldInput(): void
{
    unset($_SESSION['old_input']);
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}
