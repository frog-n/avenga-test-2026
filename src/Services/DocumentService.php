<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\DocumentRepositoryInterface;
use App\DTO\Document;

readonly class DocumentService
{
    public function __construct(
        private DocumentRepositoryInterface $repository,
    )
    {
    }

    /**
     * @param array<string> $tenantIds
     * @return array<Document>
     */
    public function allTenantDocuments(array $tenantIds): array
    {
        return $this->repository->getByTenantIds($tenantIds);
    }
}
