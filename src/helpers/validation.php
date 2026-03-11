<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Validation Helpers
|--------------------------------------------------------------------------
|
| Validators that don't exist in Laravel's Str:: class:
| - isEmail, isIp, isMac, isBase64
|
| For JSON/URL/UUID, use native: Str::isJson(), Str::isUrl(), Str::isUuid()
|
*/

if (! function_exists('is_valid_email')) {
    /**
     * Check if string is a valid email address.
     */
    function is_valid_email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (! function_exists('is_valid_ip')) {
    /**
     * Check if string is a valid IP address (IPv4 or IPv6).
     */
    function is_valid_ip(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }
}

if (! function_exists('is_valid_mac')) {
    /**
     * Check if string is a valid MAC address.
     */
    function is_valid_mac(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_MAC) !== false;
    }
}

if (! function_exists('is_valid_base64')) {
    /**
     * Check if string is valid base64 encoded.
     */
    function is_valid_base64(string $value): bool
    {
        if ('' === $value) {
            return false;
        }

        $decoded = base64_decode($value, true);

        return false !== $decoded && base64_encode($decoded) === $value;
    }
}
