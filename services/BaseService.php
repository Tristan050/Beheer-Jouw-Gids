<?php

abstract class BaseService
{
    protected function error(string $key, string $message, string $redirect): array
    {
        return [
            'ok' => false,
            'flash_key' => $key,
            'message' => $message,
            'redirect' => $redirect,
        ];
    }

    protected function success(string $key, string $message, string $redirect): array
    {
        return [
            'ok' => true,
            'flash_key' => $key,
            'message' => $message,
            'redirect' => $redirect,
        ];
    }
}
