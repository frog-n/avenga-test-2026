<?php

declare(strict_types=1);

namespace Integration\Rules;

use App\Application;
use App\DTO\Document;
use App\Rules\MaxSizeRule;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MaxSizeRuleTest extends TestCase
{

    public static function maxSizeProvider(): Generator
    {
        yield 'within limit' => [
            'content'       => 'Small content',
            'maxBytes'      => 100,
            'expectedError' => false,
        ];

        yield 'at limit' => [
            'content'       => '12345',
            'maxBytes'      => 5,
            'expectedError' => false,
        ];

        yield 'exceeds limit' => [
            'content'       => 'Too long content',
            'maxBytes'      => 5,
            'expectedError' => true,
        ];

        yield 'zero limit' => [
            'content'       => 'a',
            'maxBytes'      => 0,
            'expectedError' => true,
        ];
    }

    #[DataProvider('maxSizeProvider')]
    public function testValidate(string $content, int $maxBytes, bool $expectedError): void
    {
        $rule     = new MaxSizeRule(['value' => $maxBytes]);
        $document = new Document('id', 'tenant', $content, []);

        $error = $rule->validate($document);

        if ($expectedError) {
            $this->assertNotNull($error);
            $this->assertEquals('max_size', $error->ruleName);
            $this->assertStringContainsString('exceeds maximum size', $error->message);
        } else {
            $this->assertNull($error);
        }
    }
}
