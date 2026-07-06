<?php

declare(strict_types=1);

namespace App\Validators;

use App\Contracts\{
    DocumentValidatorFactoryInterface,
    DocumentValidatorInterface};

class DocumentValidatorFactory implements DocumentValidatorFactoryInterface
{
    public function create(): DocumentValidatorInterface
    {
        return new DocumentValidator();
    }
}