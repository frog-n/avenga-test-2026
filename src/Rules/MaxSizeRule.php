<?php

declare(strict_types=1);

namespace App\Rules;

use App\Contracts\RuleInterface;
use App\DTO\Document;
use App\DTO\ValidationError;

class MaxSizeRule extends BaseRule
{
    private int $maxBytes;

    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
        $this->maxBytes = (int)($parameters['value'] ?? 0);
    }

    public function validate(Document $document): ?ValidationError
    {
        if (mb_strlen($document->content) > $this->maxBytes) {
            return new ValidationError(
                $this->name,
                sprintf('Document content exceeds maximum size of %d bytes.', $this->maxBytes)
            );
        }

        return null;
    }
}
