<?php

declare(strict_types=1);

namespace Gizipp\IsbnToolkit\Tests;

use Gizipp\IsbnToolkit;
use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase
{
    /** @test */
    public function is_valid_isbn_works(): void
    {
        $this->assertTrue(IsbnToolkit\is_valid_isbn('9780132350884'));
        $this->assertFalse(IsbnToolkit\is_valid_isbn('12345'));
    }

    /** @test */
    public function parse_isbn_works(): void
    {
        $isbn = IsbnToolkit\parse_isbn('9780132350884');
        $this->assertInstanceOf(IsbnToolkit\ISBN::class, $isbn);
        $this->assertTrue($isbn->isValid());
    }

    /** @test */
    public function isbn10_to_13_works(): void
    {
        $this->assertSame('9780132350884', IsbnToolkit\isbn10_to_13('0132350882'));
    }

    /** @test */
    public function isbn13_to_10_works(): void
    {
        $this->assertSame('0132350882', IsbnToolkit\isbn13_to_10('9780132350884'));
    }
}
