<?php

declare(strict_types=1);

namespace Zairakai\LaravelEssentials\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class HelpersTest extends TestCase
{
    // Math helpers

    #[Test]
    public function it_detects_even_numbers(): void
    {
        self::assertTrue(is_even(0));
        self::assertTrue(is_even(2));
        self::assertTrue(is_even(-4));
        self::assertFalse(is_even(1));
        self::assertFalse(is_even(-3));
    }

    #[Test]
    public function it_detects_odd_numbers(): void
    {
        self::assertTrue(is_odd(1));
        self::assertTrue(is_odd(-3));
        self::assertFalse(is_odd(0));
        self::assertFalse(is_odd(2));
    }

    // Format helpers

    #[Test]
    public function it_formats_numbers_with_locale_separators(): void
    {
        self::assertEquals('1 234', number_format_locale(1234));
        self::assertEquals('1 234,56', number_format_locale(1234.56, 2));
        self::assertEquals('1,234.56', number_format_locale(
            1234.56,
            2,
            '.',
            ',',
        ));
    }

    // String helpers

    #[Test]
    public function it_generates_random_string_with_correct_length_and_charset(): void
    {
        self::assertEquals(10, strlen(generate_random_string(10)));
        self::assertMatchesRegularExpression('/^[A-Z]+$/', generate_random_string(10, 'ALPHA'));
        self::assertMatchesRegularExpression('/^[a-z]+$/', generate_random_string(10, 'ALPHA_LOWER'));
        self::assertMatchesRegularExpression('/^\d+$/', generate_random_string(10, 'NUMERIC'));
    }

    #[Test]
    public function it_preserves_non_string_values_in_recursive_array_replace(): void
    {
        $input    = [
            'label'   => 'foo bar',
            'count'   => 42,
            'active'  => true,
            'ratio'   => 3.14,
            'nothing' => null,
        ];
        $expected = [
            'label'   => 'bar bar',
            'count'   => 42,
            'active'  => true,
            'ratio'   => 3.14,
            'nothing' => null,
        ];

        self::assertEquals($expected, recursive_array_replace(
            'foo',
            'bar',
            $input,
        ));
    }

    #[Test]
    public function it_replaces_in_array_keys_recursively(): void
    {
        $input    = [
            'foo_key' => 'value',
        ];
        $expected = [
            'bar_key' => 'value',
        ];

        self::assertEquals($expected, recursive_array_replace(
            'foo',
            'bar',
            $input,
            true,
        ));
    }

    // Array helpers

    #[Test]
    public function it_replaces_in_array_values_recursively(): void
    {
        $input    = [
            'key'    => 'foo value',
            'nested' => [
                'deep' => 'foo bar',
            ],
        ];
        $expected = [
            'key'    => 'bar value',
            'nested' => [
                'deep' => 'bar bar',
            ],
        ];

        self::assertEquals($expected, recursive_array_replace(
            'foo',
            'bar',
            $input,
        ));
    }

    #[Test]
    public function it_returns_true_only_for_strict_false(): void
    {
        self::assertTrue(is_false(false));
        self::assertFalse(is_false(true));
        self::assertFalse(is_false(0));
        self::assertFalse(is_false(''));
    }

    // Boolean helpers

    #[Test]
    public function it_returns_true_only_for_strict_true(): void
    {
        self::assertTrue(is_true(true));
        self::assertFalse(is_true(false));
        self::assertFalse(is_true(1));
        self::assertFalse(is_true('true'));
    }

    // Filesystem helpers

    #[Test]
    public function it_sanitizes_filenames_by_removing_special_characters(): void
    {
        self::assertEquals('hello-world.txt', sanitize_filename('hello-world.txt'));
        self::assertEquals('helloworld.txt', sanitize_filename('hello@#$%world.txt'));
        self::assertEquals('file', sanitize_filename(''));
        self::assertEquals('file', sanitize_filename('@#$%'));
    }

    #[Test]
    public function it_validates_base64_strings(): void
    {
        self::assertTrue(is_valid_base64('SGVsbG8gV29ybGQ='));
        self::assertTrue(is_valid_base64('dGVzdA=='));
        self::assertFalse(is_valid_base64(''));
        self::assertFalse(is_valid_base64('not valid!'));
    }

    // Validation helpers

    #[Test]
    public function it_validates_email_addresses(): void
    {
        self::assertTrue(is_valid_email('test@example.com'));
        self::assertTrue(is_valid_email('user+tag@domain.co.uk'));
        self::assertFalse(is_valid_email('invalid'));
        self::assertFalse(is_valid_email('@domain.com'));
    }

    #[Test]
    public function it_validates_ip_addresses(): void
    {
        self::assertTrue(is_valid_ip('192.168.1.1'));
        self::assertTrue(is_valid_ip('::1'));
        self::assertFalse(is_valid_ip('256.256.256.256'));
        self::assertFalse(is_valid_ip('invalid'));
    }

    #[Test]
    public function it_validates_mac_addresses(): void
    {
        self::assertTrue(is_valid_mac('00:1A:2B:3C:4D:5E'));
        self::assertTrue(is_valid_mac('00-1A-2B-3C-4D-5E'));
        self::assertFalse(is_valid_mac('invalid'));
    }
}
