<?php

class HttpException extends Exception
{
    protected int $statusCode;

    public function __construct(int $statusCode, string $message = '')
    {
        $this->statusCode = $statusCode;

        if ($message === '') {
            $message = $this->getDefaultMessage($statusCode);
        }

        parent::__construct($message, $statusCode);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    private function getDefaultMessage(int $statusCode): string
    {
        return match ($statusCode) {
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
            default => 'Unknown Error',
        };
    }
}