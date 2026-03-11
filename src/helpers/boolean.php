<?php

declare(strict_types=1);

if (! function_exists('is_true')) {
    /**
     * Check if value is strictly true (not just truthy).
     */
    function is_true(mixed $value): bool
    {
        return true === $value;
    }
}

if (! function_exists('is_false')) {
    /**
     * Check if value is strictly false (not just falsy).
     */
    function is_false(mixed $value): bool
    {
        return false === $value;
    }
}
