<?php

declare(strict_types=1);

namespace App\Rules;

use App\Application;
use App\Contracts\RuleInterface;
use App\DTO\Document;
use App\DTO\ValidationError;

abstract class BaseRule implements RuleInterface
{
    protected string $name;

    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(array $parameters)
    {
        $this->name = Application::resolve('rules')->getRuleName(static::class);
    }

    /**
     * Validation logic.
     */
    abstract public function validate(Document $document): ?ValidationError;

}