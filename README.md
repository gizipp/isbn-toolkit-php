# isbn-toolkit

[![Latest Version](https://img.shields.io/packagist/v/gizipp/isbn-toolkit)](https://packagist.org/packages/gizipp/isbn-toolkit)
[![CI](https://github.com/gizipp/isbn-toolkit-php/actions/workflows/ci.yml/badge.svg)](https://github.com/gizipp/isbn-toolkit-php/actions/workflows/ci.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

All-in-one ISBN toolkit for PHP. Validate, convert, and lookup book metadata. With Laravel support.

## Installation

```bash
composer require gizipp/isbn-toolkit
```

## Quick Start

```php
use Gizipp\IsbnToolkit\ISBN;
use Gizipp\IsbnToolkit\Validator;
use Gizipp\IsbnToolkit\Lookup;

// Validate
Validator::isValid('978-0-13-235088-4');  // true
Validator::isValid('1234567890');          // false

// Parse
$isbn = new ISBN('978-0-13-235088-4');
$isbn->isValid();      // true
$isbn->isISBN13();     // true
$isbn->toISBN13();     // '9780132350884'
$isbn->toISBN10();     // '0132350882'
$isbn->formatted();    // '978-0-13-235088-4'

// Lookup metadata
$book = Lookup::fetch('9780132350884');
$book->title;       // 'Clean Code'
$book->authors;     // ['Robert C. Martin']
$book->isFound();   // true
```

## Features

### Validate ISBNs

```php
use Gizipp\IsbnToolkit\Validator;

Validator::isValidISBN13('9780132350884');  // true
Validator::isValidISBN10('0132350882');     // true
Validator::isValidISBN10('080442957X');     // true (X check digit)
Validator::isValid('978-0-13-235088-4');   // true (auto-detect)
```

### Convert Between Formats

```php
use Gizipp\IsbnToolkit\Converter;

Converter::isbn10to13('0132350882');      // '9780132350884'
Converter::isbn13to10('9780132350884');   // '0132350882'
Converter::formatISBN13('9780132350884'); // '978-0-13-235088-4'
```

### Lookup Book Metadata

```php
use Gizipp\IsbnToolkit\Lookup;

// From Open Library (default, free, no API key)
$book = Lookup::fetch('9780132350884');

// From Google Books
$book = Lookup::fetch('9780132350884', 'google_books');

$book->title;         // 'Clean Code'
$book->authors;       // ['Robert C. Martin']
$book->publisher;     // 'Prentice Hall'
$book->pages;         // 464
$book->cover;         // 'https://...'
$book->publishedDate; // '2008'
```

### Laravel Validation

```php
use Gizipp\IsbnToolkit\Laravel\IsbnRule;

// In a FormRequest
public function rules(): array
{
    return [
        'isbn' => ['required', new IsbnRule()],
        'isbn13' => ['required', new IsbnRule(format: 'isbn13')],
        'isbn10' => ['required', new IsbnRule(format: 'isbn10')],
    ];
}
```

### Helper Functions

```php
use function Gizipp\IsbnToolkit\is_valid_isbn;
use function Gizipp\IsbnToolkit\parse_isbn;
use function Gizipp\IsbnToolkit\isbn10_to_13;
use function Gizipp\IsbnToolkit\isbn13_to_10;
use function Gizipp\IsbnToolkit\lookup_isbn;

is_valid_isbn('9780132350884');   // true
$isbn = parse_isbn('0132350882'); // ISBN object
isbn10_to_13('0132350882');      // '9780132350884'
```

## ISBN Formats

| Format | Length | Example | Check |
|--------|--------|---------|-------|
| ISBN-10 | 10 digits | `0132350882` | Mod 11 |
| ISBN-13 | 13 digits | `9780132350884` | Mod 10 |
| EAN-13 | 13 digits | `9780132350884` | Same as ISBN-13 |

## Development

```bash
git clone https://github.com/gizipp/isbn-toolkit-php.git
cd isbn-toolkit-php
composer install
composer test
```

## Related

- [isbn-toolkit-ruby](https://github.com/gizipp/isbn-toolkit-ruby) — Ruby version (RubyGems)
- [isbn_toolkit](https://github.com/gizipp/isbn_toolkit) — JavaScript/TypeScript version (npm)

## License

[MIT](LICENSE)
