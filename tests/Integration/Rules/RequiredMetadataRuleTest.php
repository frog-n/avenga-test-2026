<?php

declare(strict_types=1);

namespace Integration\Rules;

use App\Application;
use App\DTO\Document;
use App\Rules\RequiredMetadataRule;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RequiredMetadataRuleTest extends TestCase
{
    public static function requiredMetadataProvider(): Generator
    {
        yield 'all fields present' => [
            'metadata'       => ['author' => 'Junie', 'date' => '2025-01-01'],
            'requiredFields' => ['author', 'date'],
            'expectedError'  => false,
        ];

        yield 'missing one field' => [
            'metadata'       => ['author' => 'Junie'],
            'requiredFields' => ['author', 'date'],
            'expectedError'  => true,
        ];

        yield 'missing all fields' => [
            'metadata'       => [],
            'requiredFields' => ['author', 'date'],
            'expectedError'  => true,
        ];

        yield 'no required fields' => [
            'metadata'       => [],
            'requiredFields' => [],
            'expectedError'  => false,
        ];
    }

    /**
     * @param array<string, mixed> $metadata
     * @param array<string> $requiredFields
     */
    #[DataProvider('requiredMetadataProvider')]
    public function testValidate(array $metadata, array $requiredFields, bool $expectedError): void
    {
        $rule     = new RequiredMetadataRule(['value' => $requiredFields]);
        $document = new Document('id', 'tenant', 'content', $metadata);

        $error = $rule->validate($document);

        if ($expectedError) {
            $this->assertNotNull($error);
            $this->assertEquals('required_metadata', $error->ruleName);
            $this->assertStringContainsString('Missing required metadata fields', $error->message);
        } else {
            $this->assertNull($error);
        }
    }
}
