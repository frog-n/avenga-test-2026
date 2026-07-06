<?php

declare(strict_types=1);

namespace App\DTO;

readonly class ValidationResult
{
    /**
     * @param array<string, ValidationError> $errors
     */
    public function __construct(
        public bool  $isValid,
        public array $errors = []
    )
    {
    }
}
