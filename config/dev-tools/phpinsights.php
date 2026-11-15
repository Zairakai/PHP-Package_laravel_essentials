<?php

declare(strict_types=1);

use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenDefineFunctions;

// Only what is specific to the project, and why: the base configuration is in vendor/zairakai/laravel-dev-tools.
// For the lists (remove, add, exclude) use the spread operator: array_replace_recursive() replaces by index.
$baseConfig = require dirname(__DIR__, 2) . '/vendor/zairakai/laravel-dev-tools/config/phpinsights.php';

// Example, with `use NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh;`:
// $baseConfig['config'][CyclomaticComplexityIsHigh::class] = [
//     ...($baseConfig['config'][CyclomaticComplexityIsHigh::class] ?? []),
//     'exclude' => [
//         ...($baseConfig['config'][CyclomaticComplexityIsHigh::class]['exclude'] ?? []),
//         'Console/Commands/ImportCommand.php',
//     ],
// ];

// This package defines global helper functions: it is its purpose, so the rule that forbids them is removed.
$baseConfig['remove'] = [
    ...($baseConfig['remove'] ?? []),
    ForbiddenDefineFunctions::class,
];

return $baseConfig;
