<?php

declare(strict_types=1);

namespace App\Repositories\File;

use App\Contracts\DocumentRepositoryInterface;
use App\DTO\Document;

class FileDocumentRepository extends FileBaseRepository implements DocumentRepositoryInterface
{
    public function findById(string $id): ?Document
    {
        foreach ($this->entities as $entity) {
            if ($entity['id'] === $id) {
                return $this->hydrate($entity);
            }
        }

        return null;
    }

    /**
     * @param array<string> $tenantIds
     * @return array<Document>
     */
    public function getByTenantIds(array $tenantIds): array
    {
        $filtered = array_filter($this->entities, fn($entity) => in_array($entity['tenant_id'], $tenantIds));
        return array_map(fn($entity) => $this->hydrate($entity), $filtered);
    }

    /**
     * @param array<string, mixed> $entity
     */
    private function hydrate(array $entity): Document
    {
        return new Document(
            id:       $entity['id'],
            tenantId: $entity['tenant_id'],
            content:  $entity['content'] ?? '',
            metadata: $entity['metadata'] ?? []
        );
    }
}
