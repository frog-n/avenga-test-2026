<?php

declare(strict_types=1);

namespace App\DTO;

readonly class TenantRule
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        public string $tenantId,
        public string $rule,
        public array  $parameters
    )
    {
    }
}
