<?php

abstract class BaseService
{
    protected function error(string $key, string $message, string $redirect): array
    {
        return $this->response(false, $message, [
            'flash_key' => $key,
            'redirect' => $redirect,
        ]);
    }

    protected function success(string $key, string $message, string $redirect): array
    {
        return $this->response(true, $message, [
            'flash_key' => $key,
            'redirect' => $redirect,
        ]);
    }

    protected function response(
        bool $success,
        ?string $message = null,
        array $extra = []
    ): array {
        return [
            'success' => $success,
            'message' => $message,
            ...$extra,
        ];
    }
}
