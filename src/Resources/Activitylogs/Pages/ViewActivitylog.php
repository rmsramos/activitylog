<?php

namespace Rmsramos\Activitylog\Resources\Activitylogs\Pages;

use Filament\Resources\Pages\ViewRecord;
use Rmsramos\Activitylog\Resources\Activitylogs\ActivitylogResource;

class ViewActivitylog extends ViewRecord
{
    public static function getResource(): string
    {
        return ActivitylogResource::class;
    }
}
