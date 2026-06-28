<?php

declare(strict_types=1);

namespace Gizipp\IsbnToolkit\Tests;

use Gizipp\IsbnToolkit\Converter;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    /** @test */
    public function it_converts_isbn10_to_13(): void
    {
        $this->assertSame('9780132350884', Converter::isbn10to13('0132350882'));
    }

    /** @test */
    public function it_converts_isbn10_with_x_check(): void
    {
        $result = Converter::isbn10to13('080442957X');
        $this->assertSame(13, strlen($result));
        $this->assertStringStartsWith('978', $result);
    }

    /** @test */
    public function it_throws_for_invalid_isbn10_length(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Converter::isbn10to13('123');
    }

    /** @test */
    public function it_converts_isbn13_to_10(): void
    {
        $this->assertSame('0132350882', Converter::isbn13to10('9780132350884'));
    }

    /** @test */
    public function it_throws_for_979_prefix(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('979');
        Converter::isbn13to10('9791234567896');
    }

    /** @test */
    public function it_calculates_isbn13_check(): void
    {
        $this->assertSame('4', Converter::calculateISBN13Check('978013235088'));
    }

    /** @test */
    public function it_calculates_isbn10_check(): void
    {
        $this->assertSame('2', Converter::calculateISBN10Check('013235088'));
        $this->assertSame('X', Converter::calculateISBN10Check('080442957'));
    }

    /** @test */
    public function it_formats_isbn13(): void
    {
        $this->assertSame('978-0-13-235088-4', Converter::formatISBN13('9780132350884'));
    }

    /** @test */
    public function it_formats_isbn10(): void
    {
        $this->assertSame('0-13-235088-2', Converter::formatISBN10('0132350882'));
    }
}
