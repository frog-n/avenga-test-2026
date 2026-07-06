<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\Document;
use App\DTO\ValidationError;

interface RuleInterface
{
    /**
     * Validation logic.
     */
    public function validate(Document $document): ?ValidationError;
}
