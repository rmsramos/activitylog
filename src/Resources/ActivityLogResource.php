<?php

use Filament\Resources\Resource;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    public static function getNavigationIcon(): string
    {
        return config('activitylog.navigation.activitylog.icon');
    }

    public static function getNavigationSort(): ?int
    {
        return config('activitylog.navigation.activitylog.sort');
    }

    public static function getNavigationGroup(): ?string
    {
        return config('activitylog.navigation.activitylog.group');
    }

    public static function getLabel(): string
    {
        return config('activitylog.navigation.activitylog.label');
    }

    public static function getPluralLabel(): string
    {
        return config('activitylog.navigation.activitylog.plural-label');
    }
}
