<?php

namespace App\Contracts;

interface DocumentValidatorFactoryInterface
{
    public function create(): DocumentValidatorInterface;
}