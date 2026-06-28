<?php

declare(strict_types=1);

namespace Gizipp\IsbnToolkit\Tests;

use Gizipp\IsbnToolkit\ISBN;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ISBNTest extends TestCase
{
    /** @test */
    public function it_parses_clean_digits(): void
    {
        $isbn = new ISBN('9780132350884');
        $this->assertSame('9780132350884', $isbn->getDigits());
    }

    /** @test */
    public function it_strips_hyphens_and_spaces(): void
    {
        $isbn = new ISBN('978-0-13-235088-4');
        $this->assertSame('9780132350884', $isbn->getDigits());
    }

    /** @test */
    public function it_throws_for_empty_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ISBN('');
    }

    /** @test */
    public function it_validates_isbn13(): void
    {
        $this->assertTrue((new ISBN('9780132350884'))->isValid());
        $this->assertTrue((new ISBN('978-0-13-235088-4'))->isValid());
        $this->assertFalse((new ISBN('9780132350885'))->isValid());
    }

    /** @test */
    public function it_validates_isbn10(): void
    {
        $this->assertTrue((new ISBN('0132350882'))->isValid());
        $this->assertTrue((new ISBN('080442957X'))->isValid());
        $this->assertFalse((new ISBN('0132350883'))->isValid());
    }

    /** @test */
    public function it_detects_isbn13(): void
    {
        $isbn = new ISBN('9780132350884');
        $this->assertTrue($isbn->isISBN13());
        $this->assertFalse($isbn->isISBN10());
    }

    /** @test */
    public function it_detects_isbn10(): void
    {
        $isbn = new ISBN('0132350882');
        $this->assertTrue($isbn->isISBN10());
        $this->assertFalse($isbn->isISBN13());
    }

    /** @test */
    public function it_converts_to_isbn13(): void
    {
        $this->assertSame('9780132350884', (new ISBN('0132350882'))->toISBN13());
        $this->assertSame('9780132350884', (new ISBN('9780132350884'))->toISBN13());
    }

    /** @test */
    public function it_converts_to_isbn10(): void
    {
        $this->assertSame('0132350882', (new ISBN('9780132350884'))->toISBN10());
        $this->assertSame('0132350882', (new ISBN('0132350882'))->toISBN10());
    }

    /** @test */
    public function it_throws_for_979_prefix_isbn10(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new ISBN('9791234567896'))->toISBN10();
    }

    /** @test */
    public function it_formats_with_hyphens(): void
    {
        $this->assertSame('978-0-13-235088-4', (new ISBN('9780132350884'))->formatted());
    }

    /** @test */
    public function it_converts_to_string(): void
    {
        $isbn = new ISBN('978-0-13-235088-4');
        $this->assertSame('9780132350884', (string) $isbn);
    }
}
