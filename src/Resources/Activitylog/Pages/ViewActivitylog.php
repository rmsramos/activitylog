<?php

namespace Rmsramos\Activitylog\Resources\Activitylog\Pages;

use Filament\Resources\Pages\ViewRecord;
use Rmsramos\Activitylog\Resources\Activitylog\ActivitylogResource;

class ViewActivitylog extends ViewRecord
{
    public static function getResource(): string
    {
        return ActivitylogResource::class;
    }
}
