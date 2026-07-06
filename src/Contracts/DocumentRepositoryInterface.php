<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\Document;

interface DocumentRepositoryInterface
{
    public function findById(string $id): ?Document;

    /**
     * @param array<string> $tenantIds
     * @return array<Document>
     */
    public function getByTenantIds(array $tenantIds): array;
}
