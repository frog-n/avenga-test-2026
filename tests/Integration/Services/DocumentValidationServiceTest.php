<?php

declare(strict_types=1);

namespace Integration\Services;

use App\Application;
use App\Validators\DocumentValidatorFactory;
use App\DTO\{
    Document,
    TenantRule};
use App\Services\DocumentValidationService;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DocumentValidationServiceTest extends TestCase
{
    private DocumentValidationService $service;

    protected function setUp(): void
    {
        $documentValidatorFactory = new DocumentValidatorFactory();
        $this->service            = new DocumentValidationService($documentValidatorFactory);
    }

    public static function validationProvider(): Generator
    {
        yield 'valid document passes' => [
            'document'      => new Document(
                id:       'doc_001',
                tenantId: 'tenant_001',
                content:  'Hello world',
                metadata: ['author' => 'Junie']
            ),
            'rules'         => [
                new TenantRule(tenantId: 'tenant_001', rule: 'max_size', parameters: ['value' => 100]),
                new TenantRule(tenantId: 'tenant_001', rule: 'required_metadata', parameters: ['value' => ['author']])
            ],
            'expectedValid' => true,
        ];

        yield 'invalid document collects all errors' => [
            'document'           => new Document(
                id:       'doc_002',
                tenantId: 'tenant_002',
                content:  str_repeat('This prohibited word is too long', 10),
                metadata: ['author' => 'Junie'] // missing 'date'
            ),
            'rules'              => [
                new TenantRule(tenantId: 'tenant_002', rule: 'max_size', parameters: ['value' => 100]),
                new TenantRule(tenantId: 'tenant_002', rule: 'required_metadata', parameters: ['value' => ['date']]),
                new TenantRule(tenantId: 'tenant_002', rule: 'prohibited_terms', parameters: ['value' => ['prohibited word']])
            ],
            'expectedValid'      => false,
            'expectedErrorRules' => ['max_size', 'required_metadata', 'prohibited_terms'],
        ];

        yield 'empty rules for tenant' => [
            'document'      => new Document(
                id:       'doc_003',
                tenantId: 'tenant_003',
                content:  'Anything',
                metadata: []
            ),
            'rules'         => [],
            'expectedValid' => true,
        ];
    }

    /**
     * @param array<TenantRule> $rules
     * @param array<string> $expectedErrorRules
     */
    #[DataProvider('validationProvider')]
    public function testValidate(Document $document, array $rules, bool $expectedValid, array $expectedErrorRules = []): void
    {
        $result = $this->service->validate($document, $rules);

        $this->assertEquals($expectedValid, $result->isValid);
        $this->assertCount(count($expectedErrorRules), $result->errors);

        $ruleNames = array_map(fn($e) => $e->ruleName, $result->errors);
        foreach ($expectedErrorRules as $expectedRule) {
            $this->assertContains($expectedRule, $ruleNames);
        }
    }
}
