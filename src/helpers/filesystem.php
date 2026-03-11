<?php

declare(strict_types=1);

if (! function_exists('sanitize_filename')) {
    /**
     * Sanitize a filename by removing unsafe characters.
     */
    function sanitize_filename(string $filename): string
    {
        $filename = preg_replace('/[^a-zA-Z0-9_\-.]/', '', $filename) ?? '';

        return '' === $filename ? 'file' : $filename;
    }
}
