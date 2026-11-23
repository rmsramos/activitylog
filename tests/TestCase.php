<?php

namespace Rmsramos\Activitylog\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Rmsramos\Activitylog\ActivitylogServiceProvider;
use Rmsramos\Activitylog\Tests\Fixtures\AdminPanelProvider;

abstract class TestCase extends Orchestra
{
    /**
     * Loads Filament, Livewire and spatie/laravel-activitylog through package discovery,
     * so the provider list does not have to be kept in sync with each Filament major.
     */
    protected $enablesPackageDiscoveries = true;

    protected function getPackageProviders($app): array
    {
        return [
            ActivitylogServiceProvider::class,
            AdminPanelProvider::class,
        ];
    }
}
