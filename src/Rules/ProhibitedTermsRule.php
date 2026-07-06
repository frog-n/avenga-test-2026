<?php

declare(strict_types=1);

namespace App\Rules;

use App\DTO\Document;
use App\DTO\ValidationError;

class ProhibitedTermsRule extends BaseRule
{
    /**
     * @var array<string> $words
     */
    private array $words;

    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
        $this->words = (array)($parameters['value'] ?? []);
    }

    public function validate(Document $document): ?ValidationError
    {
        $foundWords = [];
        $content    = strtolower($document->content);

        foreach ($this->words as $word) {
            if (str_contains($content, strtolower($word))) {
                $foundWords[] = $word;
            }
        }

        if (!empty($foundWords)) {
            return new ValidationError(
                $this->name,
                sprintf('Document contains prohibited terms: %s', implode(', ', $foundWords))
            );
        }

        return null;
    }
}
