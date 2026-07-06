<?php

declare(strict_types=1);

namespace App\Repositories\File;

use JsonException;
use InvalidArgumentException;

abstract class FileBaseRepository
{
    /**
     * @var array<array<string, mixed>>
     */
    protected array $entities = [];

    public function __construct(string $filePath)
    {
        // In this case, we will ignore the absence of the file since this processing is not needed for the demo.
        if (file_exists($filePath)) {
            try {
                $content        = file_get_contents($filePath);
                $this->entities = json_decode($content ?: '', true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                throw new InvalidArgumentException("Invalid JSON in file [{$filePath}]: " . $e->getMessage(), previous: $e);
            }
        }
    }
}
