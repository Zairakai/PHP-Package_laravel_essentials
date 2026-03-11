<?php

declare(strict_types=1);

if (! function_exists('generate_random_string')) {
    /**
     * Generate random string with specific character types.
     *
     * Unlike Str::random() which generates mixed alphanumeric,
     * this allows: ALPHA (uppercase), ALPHA_LOWER, NUMERIC, or ALPHANUMERIC.
     */
    function generate_random_string(int $length = 25, string $type = 'ALPHANUMERIC'): string
    {
        $characters = match ($type) {
            'ALPHA'       => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'ALPHA_LOWER' => 'abcdefghijklmnopqrstuvwxyz',
            'NUMERIC'     => '0123456789',
            default       => 'abcdefghijkmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        };

        $maxIndex = strlen($characters) - 1;
        $result   = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[random_int(0, $maxIndex)];
        }

        return $result;
    }
}
