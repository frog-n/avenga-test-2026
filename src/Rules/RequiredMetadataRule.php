<?php

declare(strict_types=1);

namespace App\Rules;

use App\DTO\Document;
use App\DTO\ValidationError;

class RequiredMetadataRule extends BaseRule
{

    /**
     * @var array<string> $requiredFields
     */
    private array $requiredFields;

    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
        $this->requiredFields = (array)($parameters['value'] ?? []);
    }

    public function validate(Document $document): ?ValidationError
    {
        $missingFields = [];
        foreach ($this->requiredFields as $field) {
            if (!isset($document->metadata[$field])) {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            return new ValidationError(
                $this->name,
                sprintf('Missing required metadata fields: %s', implode(', ', $missingFields))
            );
        }

        return null;
    }
}
