<?php

declare(strict_types=1);

namespace Gizipp\IsbnToolkit;

use InvalidArgumentException;

/**
 * Convert between ISBN-10, ISBN-13, and EAN-13 formats.
 */
final class Converter
{
    /**
     * Calculate ISBN-13 check digit (first 12 digits -> check digit).
     */
    public static function calculateISBN13Check(string $prefix): string
    {
        $digits = preg_replace('/[^0-9]/', '', $prefix);
        if (strlen($digits) !== 12) {
            throw new InvalidArgumentException('Need 12 digits for ISBN-13 check');
        }

        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $weight = ($i % 2 === 0) ? 1 : 3;
            $sum += (int) $digits[$i] * $weight;
        }

        return (string) ((10 - ($sum % 10)) % 10);
    }

    /**
     * Calculate ISBN-10 check digit (first 9 digits -> check digit).
     */
    public static function calculateISBN10Check(string $base): string
    {
        $digits = preg_replace('/[^0-9]/', '', $base);
        if (strlen($digits) !== 9) {
            throw new InvalidArgumentException('Need 9 digits for ISBN-10 check');
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $digits[$i] * (10 - $i);
        }

        $check = (11 - ($sum % 11)) % 11;

        return ($check === 10) ? 'X' : (string) $check;
    }

    /**
     * Convert ISBN-10 to ISBN-13.
     */
    public static function isbn10to13(string $isbn10): string
    {
        $digits = strtoupper(preg_replace('/[^0-9Xx]/', '', $isbn10));
        if (strlen($digits) !== 10) {
            throw new InvalidArgumentException("Invalid ISBN-10 length: " . strlen($digits));
        }

        $prefix = '978' . substr($digits, 0, 9);
        $check = self::calculateISBN13Check($prefix);

        return $prefix . $check;
    }

    /**
     * Convert ISBN-13 to ISBN-10 (only works for 978-prefix).
     */
    public static function isbn13to10(string $isbn13): string
    {
        $digits = preg_replace('/[^0-9]/', '', $isbn13);
        if (strlen($digits) !== 13) {
            throw new InvalidArgumentException("Invalid ISBN-13 length: " . strlen($digits));
        }
        if (!str_starts_with($digits, '978')) {
            throw new InvalidArgumentException('979-prefix ISBNs cannot be converted to ISBN-10');
        }

        $base = substr($digits, 3, 9);
        $check = self::calculateISBN10Check($base);

        return $base . $check;
    }

    /**
     * Format ISBN-13 with hyphens.
     */
    public static function formatISBN13(string $isbn13): string
    {
        $digits = preg_replace('/[^0-9]/', '', $isbn13);
        if (strlen($digits) !== 13) {
            throw new InvalidArgumentException('Invalid ISBN-13 length');
        }

        return sprintf('%s-%s-%s-%s-%s',
            substr($digits, 0, 3),
            $digits[3],
            substr($digits, 4, 2),
            substr($digits, 6, 6),
            $digits[12]
        );
    }

    /**
     * Format ISBN-10 with hyphens.
     */
    public static function formatISBN10(string $isbn10): string
    {
        $digits = strtoupper(preg_replace('/[^0-9Xx]/', '', $isbn10));
        if (strlen($digits) !== 10) {
            throw new InvalidArgumentException('Invalid ISBN-10 length');
        }

        return sprintf('%s-%s-%s-%s',
            $digits[0],
            substr($digits, 1, 2),
            substr($digits, 3, 6),
            $digits[9]
        );
    }
}
