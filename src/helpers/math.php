<?php

declare(strict_types=1);

if (! function_exists('is_even')) {
    /**
     * Check if number is even.
     */
    function is_even(int $number): bool
    {
        return $number % 2 === 0;
    }
}

if (! function_exists('is_odd')) {
    /**
     * Check if number is odd.
     */
    function is_odd(int $number): bool
    {
        return $number % 2 !== 0;
    }
}
