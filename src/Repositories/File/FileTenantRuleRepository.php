<?php

declare(strict_types=1);

namespace App\Repositories\File;

use App\Contracts\TenantRuleRepositoryInterface;
use App\DTO\TenantRule;

class FileTenantRuleRepository extends FileBaseRepository implements TenantRuleRepositoryInterface
{
    /**
     * @param array<string> $tenantIds
     * @return array<TenantRule>
     */
    public function getRulesConfigByTenantIds(array $tenantIds): array
    {
        $filtered = array_filter($this->entities, fn($entity) => in_array($entity['tenant_id'], $tenantIds));
        return array_map(fn($entity) => new TenantRule(
            tenantId:   $entity['tenant_id'],
            rule:       $entity['rule'],
            parameters: (array)($entity['parameters'] ?? []),
        ), $filtered);
    }
}
