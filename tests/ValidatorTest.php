<?php

declare(strict_types=1);

namespace Gizipp\IsbnToolkit\Tests;

use Gizipp\IsbnToolkit\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /** @test */
    public function it_validates_isbn13(): void
    {
        $this->assertTrue(Validator::isValidISBN13('9780132350884'));
        $this->assertFalse(Validator::isValidISBN13('9780132350885'));
        $this->assertFalse(Validator::isValidISBN13('12345'));
        $this->assertFalse(Validator::isValidISBN13('abcdefghijklm'));
    }

    /** @test */
    public function it_validates_isbn10(): void
    {
        $this->assertTrue(Validator::isValidISBN10('0132350882'));
        $this->assertTrue(Validator::isValidISBN10('080442957X'));
        $this->assertFalse(Validator::isValidISBN10('0132350883'));
        $this->assertFalse(Validator::isValidISBN10('12345'));
    }

    /** @test */
    public function it_auto_detects_format(): void
    {
        $this->assertTrue(Validator::isValid('9780132350884'));
        $this->assertTrue(Validator::isValid('0132350882'));
        $this->assertTrue(Validator::isValid('978-0-13-235088-4'));
        $this->assertFalse(Validator::isValid('12345'));
        $this->assertFalse(Validator::isValid(''));
    }
}
