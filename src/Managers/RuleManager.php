<?php

declare(strict_types=1);

namespace App\Managers;

use App\Contracts\RuleInterface;
use App\DTO\TenantRule;
use App\Exceptions\RuleNotRegisteredException;
use InvalidArgumentException;

class RuleManager
{
    /**
     * @var array<string, class-string<RuleInterface>>
     */
    private array $registeredRules = [];

    public function getRuleName(string $class): ?string
    {
        return array_find_key($this->registeredRules, fn($ruleClass) => $ruleClass === $class);
    }

    public function register(string $name, string $ruleClass): void
    {
        if (!is_subclass_of($ruleClass, RuleInterface::class)) {
            throw new InvalidArgumentException("Class [{$ruleClass}] must implement RuleInterface.");
        }

        $this->registeredRules[$name] = $ruleClass;
    }

    public function make(TenantRule $rule): ?RuleInterface
    {
        $ruleClass = $this->registeredRules[$rule->rule] ?? null;
        if (!$ruleClass) {
            throw RuleNotRegisteredException::forName($rule->rule);
        }

        return new $ruleClass($rule->parameters);
    }
}
