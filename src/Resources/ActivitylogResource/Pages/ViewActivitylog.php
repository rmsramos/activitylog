<?php

namespace Rmsramos\Activitylog\Resources\ActivitylogResource\Pages;

use Filament\Resources\Pages\ViewRecord;

class ViewActivitylog extends ViewRecord
{
    public static function getResource(): string
    {
        return config('filament-activitylog.resources.resource');
    }
}
