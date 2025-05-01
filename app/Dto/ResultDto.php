<?php

declare(strict_types=1);

namespace App\Dto;

final class ResultDto
{
    public bool $success;

    public ?string $message;

    public ?int $response;

    public function __construct(bool $success, ?string $message, ?int $response)
    {
        $this->success = $success;
        $this->message = $message;
        $this->response = $response;
    }

    public static function ok(string $message, ?int $response = null): self
    {
        return new self(true, $message, $response);
    }

    public static function fail(string $message, ?int $response = null): self
    {
        return new self(false, $message, $response);
    }
}
