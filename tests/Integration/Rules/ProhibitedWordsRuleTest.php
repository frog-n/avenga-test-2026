<?php

declare(strict_types=1);

namespace Integration\Rules;

use App\Application;
use App\DTO\Document;
use App\Rules\ProhibitedTermsRule;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ProhibitedWordsRuleTest extends TestCase
{
    public static function banWordsProvider(): Generator
    {
        yield 'no ban words' => [
            'content'       => 'This is a clean document.',
            'banWords'      => ['prohibited', 'evil'],
            'expectedError' => false,
        ];

        yield 'contains ban word' => [
            'content'       => 'This is an prohibited document.',
            'banWords'      => ['prohibited', 'evil'],
            'expectedError' => true,
        ];

        yield 'case insensitive' => [
            'content'       => 'This is an EVIL document.',
            'banWords'      => ['evil'],
            'expectedError' => true,
        ];

        yield 'partial match' => [
            'content'       => 'This is an evildoing.',
            'banWords'      => ['evil'],
            'expectedError' => true,
        ];

        yield 'empty ban words' => [
            'content'       => 'Anything goes.',
            'banWords'      => [],
            'expectedError' => false,
        ];
    }

    /**
     * @param array<string> $banWords
     */
    #[DataProvider('banWordsProvider')]
    public function testValidate(string $content, array $banWords, bool $expectedError): void
    {
        $rule     = new ProhibitedTermsRule(['value' => $banWords]);
        $document = new Document('id', 'tenant', $content, []);

        $error = $rule->validate($document);

        if ($expectedError) {
            $this->assertNotNull($error);
            $this->assertEquals('prohibited_terms', $error->ruleName);
            $this->assertStringContainsString('Document contains prohibited terms', $error->message);
        } else {
            $this->assertNull($error);
        }
    }
}
