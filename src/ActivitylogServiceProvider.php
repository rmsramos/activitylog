<?php

namespace Rmsramos\Activitylog;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Rmsramos\Activitylog\Commands\ActivitylogCommand;

class ActivitylogServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('activitylog')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_activitylog_table')
            ->hasCommand(ActivitylogCommand::class);
    }
}
