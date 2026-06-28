<?php

declare(strict_types=1);

namespace Gizipp\IsbnToolkit;

/**
 * Quick helper functions.
 */

/**
 * Validate an ISBN string.
 */
function is_valid_isbn(string $isbn): bool
{
    return Validator::isValid($isbn);
}

/**
 * Parse an ISBN string into an ISBN object.
 */
function parse_isbn(string $isbn): ISBN
{
    return new ISBN($isbn);
}

/**
 * Convert ISBN-10 to ISBN-13.
 */
function isbn10_to_13(string $isbn10): string
{
    return Converter::isbn10to13($isbn10);
}

/**
 * Convert ISBN-13 to ISBN-10.
 */
function isbn13_to_10(string $isbn13): string
{
    return Converter::isbn13to10($isbn13);
}

/**
 * Fetch book metadata.
 */
function lookup_isbn(string $isbn, string $source = 'open_library'): BookMetadata
{
    return Lookup::fetch($isbn, $source);
}
