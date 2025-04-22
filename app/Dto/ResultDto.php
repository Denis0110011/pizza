<?php

declare(strict_types=1);

namespace App\Dto;

final class ResultDto
{
    public bool $success;

    public ?string $message;

    public function __construct(bool $Success, ?string $message)
    {
        $this->success = $Success;
        $this->message = $message;

    }

    public static function ok(string $message): self
    {
        return new self(true, $message);
    }

    public static function fail(string $message): self
    {
        return new self(false, $message);
    }
}
