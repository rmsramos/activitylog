<?php

namespace Rmsramos\Activitylog;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ActivitylogServiceProvider extends PackageServiceProvider
{
    public static string $name = 'activitylog';

    public function configurePackage(Package $package): void
    {
        $package
            ->name('activitylog')
            ->hasConfigFile('activitylog')
            ->hasViews('activitylog');
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('activitylog', __DIR__.'/../resources/dist/activitylog.css')->loadedOnRequest(),
        ], 'rmsramos/activitylog');
    }
}
