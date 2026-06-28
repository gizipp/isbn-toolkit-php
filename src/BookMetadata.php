<?php

declare(strict_types=1);

namespace Gizipp\IsbnToolkit;

/**
 * Book metadata result.
 */
final class BookMetadata
{
    public function __construct(
        public readonly string $isbn,
        public readonly ?string $title = null,
        public readonly array $authors = [],
        public readonly ?string $publisher = null,
        public readonly ?string $publishedDate = null,
        public readonly ?string $description = null,
        public readonly ?string $cover = null,
        public readonly ?int $pages = null,
        public readonly ?string $language = null,
        public readonly string $source = 'open_library',
    ) {}

    public function isFound(): bool
    {
        return $this->title !== null && $this->title !== '';
    }
}
