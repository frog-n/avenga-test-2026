<?php

declare(strict_types=1);

namespace App\Exceptions;

use InvalidArgumentException;

class RuleNotRegisteredException extends InvalidArgumentException
{
    public static function forName(string $ruleName): self
    {
        return new self("Validation rule [{$ruleName}] is not registered.");
    }
}