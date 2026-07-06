<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\TenantRule;
use App\Contracts\TenantRuleRepositoryInterface;

readonly class TenantRuleService
{
    public function __construct(
        private TenantRuleRepositoryInterface $repository,
    )
    {
    }

    /**
     * @param array<string> $tenantIds
     * @return array<TenantRule>
     */
    public function getTenantRules(array $tenantIds): array
    {
        return $this->repository->getRulesConfigByTenantIds($tenantIds);
    }
}
