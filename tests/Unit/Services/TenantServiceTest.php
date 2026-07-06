<?php

declare(strict_types=1);

namespace Unit\Services;

use Generator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\Attributes\DataProvider;

use App\DTO\Tenant;
use App\DTO\TenantRule;
use App\Services\TenantService;
use App\Contracts\TenantRepositoryInterface;
use App\Contracts\TenantRuleRepositoryInterface;

class TenantServiceTest extends TestCase
{
    private TenantService                        $tenantService;
    private TenantRepositoryInterface&MockObject $tenantRepository;

    protected function setUp(): void
    {
        $this->tenantRepository = $this->createMock(TenantRepositoryInterface::class);
        $this->tenantService    = new TenantService($this->tenantRepository);
    }

    public static function getTenantProvider(): Generator
    {
        yield 'existing tenant' => [
            'tenantId'       => 'tenant_001',
            'expectedTenant' => new Tenant('tenant_001', 'Tenant Name'),
        ];

        yield 'non-existing tenant' => [
            'tenantId'       => 'non_existent',
            'expectedTenant' => null,
        ];
    }

    #[DataProvider('getTenantProvider')]
    public function testGetTenant(string $tenantId, ?Tenant $expectedTenant): void
    {
        $this->tenantRepository->expects($this->once())
            ->method('findById')
            ->with($tenantId)
            ->willReturn($expectedTenant);

        $result = $this->tenantService->getTenant($tenantId);

        $this->assertSame($expectedTenant, $result);
    }
}
