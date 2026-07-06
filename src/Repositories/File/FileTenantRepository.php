<?php

declare(strict_types=1);

namespace App\Repositories\File;

use App\DTO\Tenant;
use App\Contracts\TenantRepositoryInterface;

class FileTenantRepository extends FileBaseRepository implements TenantRepositoryInterface
{
    /**
     * @return array<Tenant>
     */
    public function findAll(): array {
        return array_map(fn($entity) => $this->hydrate($entity), $this->entities);
    }

    public function findById(string $id): ?Tenant
    {
        foreach ($this->entities as $entity) {
            if ($entity['id'] === $id) {
                return $this->hydrate($entity);
            }
        }

        return null;
    }

    /**
     * @param array<string, mixed> $entity
     */
    private function hydrate(array $entity): Tenant
    {
        return new Tenant(
            id:   $entity['id'],
            name: $entity['name'],
        );
    }
}
