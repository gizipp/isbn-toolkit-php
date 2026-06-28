<?php

declare(strict_types=1);

namespace Gizipp\IsbnToolkit;

use InvalidArgumentException;

/**
 * Core ISBN object.
 */
final class ISBN
{
    private string $raw;
    private string $digits;

    public function __construct(string $isbn)
    {
        if ($isbn === '') {
            throw new InvalidArgumentException('ISBN cannot be empty');
        }

        $this->raw = trim($isbn);
        $this->digits = $this->sanitize($this->raw);
    }

    /**
     * Whether the ISBN passes checksum validation.
     */
    public function isValid(): bool
    {
        return match (strlen($this->digits)) {
            13 => Validator::isValidISBN13($this->digits),
            10 => Validator::isValidISBN10($this->digits),
            default => false,
        };
    }

    /**
     * Whether this is an ISBN-13.
     */
    public function isISBN13(): bool
    {
        return strlen($this->digits) === 13
            && (str_starts_with($this->digits, '978') || str_starts_with($this->digits, '979'));
    }

    /**
     * Whether this is an ISBN-10.
     */
    public function isISBN10(): bool
    {
        return strlen($this->digits) === 10;
    }

    /**
     * Convert to ISBN-13 string.
     */
    public function toISBN13(): string
    {
        if ($this->isISBN13()) {
            return $this->digits;
        }

        return Converter::isbn10to13($this->digits);
    }

    /**
     * Convert to ISBN-10 string.
     */
    public function toISBN10(): string
    {
        if ($this->isISBN10()) {
            return $this->digits;
        }

        return Converter::isbn13to10($this->digits);
    }

    /**
     * EAN-13 (same as ISBN-13 for 978/979 prefix).
     */
    public function toEAN13(): string
    {
        return $this->toISBN13();
    }

    /**
     * Format with hyphens.
     */
    public function formatted(): string
    {
        try {
            $digits = $this->isISBN13() ? $this->digits : $this->toISBN13();

            return Converter::formatISBN13($digits);
        } catch (InvalidArgumentException) {
            return Converter::formatISBN10($this->digits);
        }
    }

    /**
     * Get clean digits.
     */
    public function getDigits(): string
    {
        return $this->digits;
    }

    /**
     * Get raw input.
     */
    public function getRaw(): string
    {
        return $this->raw;
    }

    public function __toString(): string
    {
        return $this->digits;
    }

    private function sanitize(string $isbn): string
    {
        return strtoupper(preg_replace('/[^0-9Xx]/', '', $isbn));
    }
}
