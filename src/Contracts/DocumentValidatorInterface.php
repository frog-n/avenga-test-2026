<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\Document;
use App\DTO\TenantRule;
use App\DTO\ValidationResult;

interface DocumentValidatorInterface
{
    /**
     * @param array<TenantRule> $rules
     */
    public function validate(Document $document, array $rules): ValidationResult;
}
