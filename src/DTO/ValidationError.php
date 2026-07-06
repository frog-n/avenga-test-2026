<?php

declare(strict_types=1);

namespace App\DTO;

readonly class ValidationError
{
    public function __construct(
        public string $ruleName,
        public string $message
    )
    {
    }
}
