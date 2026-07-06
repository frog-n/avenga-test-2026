<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\TenantRule;

interface TenantRuleRepositoryInterface
{
    /**
     * @param array<string> $tenantIds
     * @return array<TenantRule>
     */
    public function getRulesConfigByTenantIds(array $tenantIds): array;
}
