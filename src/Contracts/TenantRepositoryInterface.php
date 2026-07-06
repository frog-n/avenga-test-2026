<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\Tenant;

interface TenantRepositoryInterface
{
    /**
     * @return array<Tenant>
     */
    public function findAll(): array;

    public function findById(string $id): ?Tenant;
}
