<?php

declare(strict_types=1);

namespace Zairakai\LaravelEssentials\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Zairakai\LaravelEssentials\EssentialsServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            EssentialsServiceProvider::class,
        ];
    }
}
