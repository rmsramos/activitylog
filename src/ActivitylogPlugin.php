<?php

namespace Rmsramos\Activitylog;

use Filament\Contracts\Plugin;
use Filament\Panel;

class ActivitylogPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'activitylog';
    }

    public function register(Panel $panel): void
    {

    }

    public function boot(Panel $panel): void
    {
        //
    }
}
