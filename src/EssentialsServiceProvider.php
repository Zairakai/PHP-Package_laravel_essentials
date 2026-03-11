<?php

declare(strict_types=1);

namespace Zairakai\LaravelEssentials;

use Illuminate\Support\ServiceProvider;
use Zairakai\LaravelEssentials\Macros\StrMacros;

class EssentialsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        StrMacros::register();
    }

    public function register(): void {}
}
