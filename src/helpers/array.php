<?php

declare(strict_types=1);

if (! function_exists('recursive_array_replace')) {
    /**
     * Recursively replace occurrences in array values or keys.
     *
     * @param array<array-key, mixed>|string $data
     *
     * @return array<array-key, mixed>|string
     */
    function recursive_array_replace(
        string $find,
        string $replace,
        array|string $data,
        bool $inKeys = false,
    ): array|string {
        if (is_string($data)) {
            return $inKeys ? $data : str_replace($find, $replace, $data);
        }

        $result = [];

        foreach ($data as $key => $value) {
            $newKey = $inKeys && is_string($key)
                ? str_replace($find, $replace, $key)
                : $key;

            $result[$newKey] = is_array($value) || is_string($value)
                ? recursive_array_replace($find, $replace, $value, $inKeys)
                : $value;
        }

        return $result;
    }
}
