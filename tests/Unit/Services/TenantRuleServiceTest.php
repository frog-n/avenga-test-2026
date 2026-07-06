<?php

declare(strict_types=1);

namespace Unit\Services;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use App\DTO\TenantRule;
use App\Services\TenantRuleService;
use App\Contracts\TenantRuleRepositoryInterface;

class TenantRuleServiceTest extends TestCase
{
    private TenantRuleService                        $tenantRuleService;
    private TenantRuleRepositoryInterface&MockObject $ruleRepository;

    protected function setUp(): void
    {
        $this->ruleRepository    = $this->createMock(TenantRuleRepositoryInterface::class);
        $this->tenantRuleService = new TenantRuleService($this->ruleRepository);
    }

    public static function getTenantRulesProvider(): Generator
    {
        yield 'multiple rules' => [
            'tenantId'      => 'tenant_001',
            'expectedRules' => [
                new TenantRule('tenant_001', 'max_size', ['value' => 100]),
                new TenantRule('tenant_001', 'prohibited_terms', ['value' => ['prohibited']]),
            ],
        ];

        yield 'no rules' => [
            'tenantId'      => 'tenant_no_rules',
            'expectedRules' => [],
        ];
    }

    /**
     * @param array<TenantRule> $expectedRules
     */
    #[DataProvider('getTenantRulesProvider')]
    public function testGetTenantRules(string $tenantId, array $expectedRules): void
    {
        $this->ruleRepository->expects($this->once())
            ->method('getRulesConfigByTenantIds')
            ->with([$tenantId])
            ->willReturn($expectedRules);

        $result = $this->tenantRuleService->getTenantRules([$tenantId]);

        $this->assertSame($expectedRules, $result);
    }
}
