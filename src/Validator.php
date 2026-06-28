<?php

declare(strict_types=1);

namespace Gizipp\IsbnToolkit;

/**
 * ISBN checksum validation.
 */
final class Validator
{
    /**
     * Validate ISBN-13 checksum (mod 10).
     */
    public static function isValidISBN13(string $digits): bool
    {
        if (!preg_match('/^\d{13}$/', $digits)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $weight = ($i % 2 === 0) ? 1 : 3;
            $sum += (int) $digits[$i] * $weight;
        }

        return $sum % 10 === 0;
    }

    /**
     * Validate ISBN-10 checksum (mod 11).
     */
    public static function isValidISBN10(string $digits): bool
    {
        if (!preg_match('/^\d{9}[\dX]$/', $digits)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $value = ($digits[$i] === 'X') ? 10 : (int) $digits[$i];
            $sum += $value * (10 - $i);
        }

        return $sum % 11 === 0;
    }

    /**
     * Auto-detect format and validate.
     */
    public static function isValid(string $isbn): bool
    {
        $digits = self::sanitize($isbn);

        return match (strlen($digits)) {
            13 => self::isValidISBN13($digits),
            10 => self::isValidISBN10($digits),
            default => false,
        };
    }

    private static function sanitize(string $isbn): string
    {
        return strtoupper(preg_replace('/[^0-9Xx]/', '', $isbn));
    }
}
