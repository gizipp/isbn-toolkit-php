<?php

declare(strict_types=1);

namespace Gizipp\IsbnToolkit;

use InvalidArgumentException;
use RuntimeException;

/**
 * Fetch book metadata from external APIs.
 */
final class Lookup
{
    /**
     * Fetch book metadata from Open Library or Google Books.
     */
    public static function fetch(string $isbn, string $source = 'open_library'): BookMetadata
    {
        $digits = strtoupper(preg_replace('/[^0-9Xx]/', '', $isbn));

        return match ($source) {
            'open_library' => self::fetchOpenLibrary($digits),
            'google_books' => self::fetchGoogleBooks($digits),
            default => throw new InvalidArgumentException("Unknown source: {$source}. Use 'open_library' or 'google_books'"),
        };
    }

    private static function fetchOpenLibrary(string $isbn): BookMetadata
    {
        $url = "https://openlibrary.org/api/books?bibkeys=ISBN:{$isbn}&format=json&jscmd=data";
        $response = self::httpGet($url);

        $data = json_decode($response, true);
        $book = $data["ISBN:{$isbn}"] ?? null;

        if (!$book) {
            return new BookMetadata(isbn: $isbn, source: 'open_library');
        }

        return new BookMetadata(
            isbn: $isbn,
            title: $book['title'] ?? null,
            authors: array_map(fn($a) => $a['name'], $book['authors'] ?? []),
            publisher: $book['publishers'][0]['name'] ?? null,
            publishedDate: $book['publish_date'] ?? null,
            description: $book['notes'] ?? $book['excerpts'][0]['text'] ?? null,
            cover: $book['cover']['large'] ?? $book['cover']['medium'] ?? null,
            pages: $book['number_of_pages'] ?? null,
            language: isset($book['languages'][0]['key'])
                ? str_replace('/languages/', '', $book['languages'][0]['key'])
                : null,
            source: 'open_library',
        );
    }

    private static function fetchGoogleBooks(string $isbn): BookMetadata
    {
        $url = "https://www.googleapis.com/books/v1/volumes?q=isbn:{$isbn}";
        $response = self::httpGet($url);

        $data = json_decode($response, true);

        if (($data['totalItems'] ?? 0) === 0) {
            return new BookMetadata(isbn: $isbn, source: 'google_books');
        }

        $info = $data['items'][0]['volumeInfo'] ?? [];

        return new BookMetadata(
            isbn: $isbn,
            title: $info['title'] ?? null,
            authors: $info['authors'] ?? [],
            publisher: $info['publisher'] ?? null,
            publishedDate: $info['publishedDate'] ?? null,
            description: $info['description'] ?? null,
            cover: $info['imageLinks']['thumbnail'] ?? null,
            pages: $info['pageCount'] ?? null,
            language: $info['language'] ?? null,
            source: 'google_books',
        );
    }

    /**
     * HTTP GET with auto-detection (Guzzle if available, file_get_contents fallback).
     */
    private static function httpGet(string $url): string
    {
        if (class_exists(\GuzzleHttp\Client::class)) {
            $client = new \GuzzleHttp\Client();
            return (string) $client->get($url)->getBody();
        }

        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'ignore_errors' => true,
            ],
        ]);

        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            throw new RuntimeException("Failed to fetch: {$url}");
        }

        return $response;
    }
}
