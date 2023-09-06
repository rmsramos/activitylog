<?php

namespace Rmsramos\Activitylog;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ActivitylogServiceProvider extends PackageServiceProvider
{
    public static string $name = 'activitylog';

    public function configurePackage(Package $package): void
    {
        $package
            ->name('activitylog')
            ->hasConfigFile('activitylog');
    }
}
