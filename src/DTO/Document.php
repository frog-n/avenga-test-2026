<?php

declare(strict_types=1);

namespace App\DTO;

readonly class Document
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public string $id,
        public string $tenantId,
        public string $content,
        public array  $metadata
    )
    {
    }
}
