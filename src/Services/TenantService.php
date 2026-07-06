<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Tenant;
use App\Contracts\TenantRepositoryInterface;

readonly class TenantService
{
    public function __construct(
        private TenantRepositoryInterface $repository,
    )
    {
    }

    /**
     * @return array<Tenant>
     */
    public function getAllTenants(): array
    {
        return $this->repository->findAll();
    }

    public function getTenant(string $tenantId): ?Tenant
    {
        return $this->repository->findById($tenantId);
    }
}
