<?php

declare(strict_types=1);

namespace Gizipp\IsbnToolkit\Laravel;

use Closure;
use Gizipp\IsbnToolkit\Validator;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Laravel validation rule for ISBN fields.
 *
 * Usage:
 *   $request->validate(['isbn' => ['required', new IsbnRule()]]);
 *   $request->validate(['isbn' => ['required', new IsbnRule(format: 'isbn13')]]);
 */
class IsbnRule implements ValidationRule
{
    public function __construct(
        private readonly ?string $format = null,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail("The :attribute must be a valid ISBN string.");

            return;
        }

        if (!Validator::isValid($value)) {
            $fail("The :attribute is not a valid ISBN.");

            return;
        }

        if ($this->format === 'isbn13') {
            $digits = strtoupper(preg_replace('/[^0-9Xx]/', '', $value));
            if (strlen($digits) !== 13) {
                $fail("The :attribute must be ISBN-13 format.");
            }
        }

        if ($this->format === 'isbn10') {
            $digits = strtoupper(preg_replace('/[^0-9Xx]/', '', $value));
            if (strlen($digits) !== 10) {
                $fail("The :attribute must be ISBN-10 format.");
            }
        }
    }
}
