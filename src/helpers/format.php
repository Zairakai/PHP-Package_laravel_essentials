<?php

declare(strict_types=1);

if (! function_exists('number_format_locale')) {
    /**
     * Format number with locale-aware separators (French by default).
     */
    function number_format_locale(
        float|int $number,
        int $decimals = 0,
        string $decimalSeparator = ',',
        string $thousandsSeparator = ' ',
    ): string {
        return number_format((float) $number, $decimals, $decimalSeparator, $thousandsSeparator);
    }
}
