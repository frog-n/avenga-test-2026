<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Document;
use App\DTO\TenantRule;
use App\DTO\ValidationResult;
use App\Contracts\DocumentValidatorFactoryInterface;

readonly class DocumentValidationService
{
    public function __construct(
        private DocumentValidatorFactoryInterface $factory,
    )
    {
    }

    /**
     * @param array<TenantRule> $rules
     */
    public function validate(Document $document, array $rules): ValidationResult
    {
        $validator = $this->factory->create();
        return $validator->validate($document, $rules);
    }
}
