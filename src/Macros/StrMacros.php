<?php

declare(strict_types=1);

namespace Zairakai\LaravelEssentials\Macros;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

/**
 * String macros that don't exist in Laravel's Str class.
 */
final class StrMacros
{
    public static function register(): void
    {
        self::registerStrMacros();
        self::registerStringableMacros();
    }

    private static function registerStringableMacros(): void
    {
        Stringable::macro('isEmail', fn (): bool => is_valid_email($this->toString()));

        Stringable::macro('isIp', fn (): bool => is_valid_ip($this->toString()));

        Stringable::macro('isMac', fn (): bool => is_valid_mac($this->toString()));

        Stringable::macro('isBase64', fn (): bool => is_valid_base64($this->toString()));
    }

    private static function registerStrMacros(): void
    {
        Str::macro('isEmail', fn (string $value): bool => is_valid_email($value));

        Str::macro('isIp', fn (string $value): bool => is_valid_ip($value));

        Str::macro('isMac', fn (string $value): bool => is_valid_mac($value));

        Str::macro('isBase64', fn (string $value): bool => is_valid_base64($value));
    }
}
