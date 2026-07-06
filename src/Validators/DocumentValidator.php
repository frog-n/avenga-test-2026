<?php

declare(strict_types=1);

namespace App\Validators;

use App\Application;
use App\Contracts\DocumentValidatorInterface;
use App\DTO\Document;
use App\DTO\TenantRule;
use App\DTO\ValidationError;
use App\DTO\ValidationResult;
use App\Exceptions\RuleNotRegisteredException;

class DocumentValidator implements DocumentValidatorInterface
{
    /**
     * @param array<TenantRule> $rules
     */
    public function validate(Document $document, array $rules): ValidationResult
    {
        $errors = [];
        foreach ($rules as $rule) {
            try {
                $validationRule = Application::resolve('rules')->make($rule);
                $error          = $validationRule->validate($document);

                if ($error !== null) {
                    $errors[$rule->rule] = $error;
                }
            } catch (RuleNotRegisteredException $exception) {
                $errors[$rule->rule] = new ValidationError(
                    ruleName: $rule->rule,
                    message:  $exception->getMessage()
                );
            }
        }

        return new ValidationResult(
            isValid: empty($errors),
            errors: $errors,
        );
    }
}
