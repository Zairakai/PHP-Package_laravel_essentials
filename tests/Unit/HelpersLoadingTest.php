<?php

declare(strict_types=1);

namespace Zairakai\LaravelEssentials\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class HelpersLoadingTest extends TestCase
{
    /**
     * @return iterable<string, array{string, list<string>}>
     */
    public static function helperFiles(): iterable
    {
        yield 'array' => ['array.php', ['recursive_array_replace']];

        yield 'boolean' => ['boolean.php', ['is_true', 'is_false']];

        yield 'filesystem' => ['filesystem.php', ['sanitize_filename']];

        yield 'format' => ['format.php', ['number_format_locale']];

        yield 'math' => ['math.php', ['is_even', 'is_odd']];

        yield 'string' => ['string.php', ['generate_random_string']];

        yield 'validation' => ['validation.php', ['is_valid_email', 'is_valid_ip', 'is_valid_mac', 'is_valid_base64']];
    }

    /**
     * @param list<string> $functions
     */
    #[Test]
    #[DataProvider('helperFiles')]
    public function it_defines_its_functions_and_can_be_loaded_again_without_redefining_them(string $file, array $functions): void
    {
        // Composer loads the helpers before the coverage starts: loading them again runs their guards under it
        require dirname(__DIR__, 2) . '/src/helpers/' . $file;

        foreach ($functions as $function) {
            self::assertTrue(function_exists($function), $function);
        }
    }
}
