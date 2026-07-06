<?php

declare(strict_types=1);

namespace Unit\Services;

use Generator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\Attributes\DataProvider;

use App\DTO\Document;
use App\Services\DocumentService;
use App\Contracts\DocumentRepositoryInterface;

class DocumentServiceTest extends TestCase
{
    private DocumentService                        $service;
    private DocumentRepositoryInterface&MockObject $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(DocumentRepositoryInterface::class);
        $this->service    = new DocumentService($this->repository);
    }

    public static function allTenantDocumentsProvider(): Generator
    {
        yield 'multiple documents' => [
            'tenantId'        => 'tenant_001',
            'mockedDocuments' => [
                new Document('doc_001', 'tenant_001', 'Content 1', []),
                new Document('doc_002', 'tenant_001', 'Content 2', ['key' => 'val']),
            ],
        ];

        yield 'no documents' => [
            'tenantId'        => 'tenant_empty',
            'mockedDocuments' => [],
        ];
    }

    /**
     * @param array<Document> $mockedDocuments
     */
    #[DataProvider('allTenantDocumentsProvider')]
    public function testAllTenantDocuments(string $tenantId, array $mockedDocuments): void
    {
        $this->repository->expects($this->once())
            ->method('getByTenantIds')
            ->with([$tenantId])
            ->willReturn($mockedDocuments);

        $result = $this->service->allTenantDocuments([$tenantId]);

        $this->assertCount(count($mockedDocuments), $result);
        $this->assertSame($mockedDocuments, $result);
    }
}
