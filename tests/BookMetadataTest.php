<?php

declare(strict_types=1);

namespace Gizipp\IsbnToolkit\Tests;

use Gizipp\IsbnToolkit\BookMetadata;
use PHPUnit\Framework\TestCase;

class BookMetadataTest extends TestCase
{
    /** @test */
    public function it_returns_not_found_when_title_is_null(): void
    {
        $metadata = new BookMetadata(isbn: '9780132350884');
        $this->assertFalse($metadata->isFound());
    }

    /** @test */
    public function it_returns_not_found_when_title_is_empty(): void
    {
        $metadata = new BookMetadata(isbn: '9780132350884', title: '');
        $this->assertFalse($metadata->isFound());
    }

    /** @test */
    public function it_returns_found_when_title_exists(): void
    {
        $metadata = new BookMetadata(isbn: '9780132350884', title: 'Clean Code');
        $this->assertTrue($metadata->isFound());
    }
}
