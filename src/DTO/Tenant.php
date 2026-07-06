<?php

declare(strict_types=1);

namespace App\DTO;

readonly class Tenant
{
    public function __construct(
        public string $id,
        public string $name
    )
    {
    }
}
