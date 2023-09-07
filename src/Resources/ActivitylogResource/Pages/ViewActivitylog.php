<?php

namespace Rmsramos\Activitylog\Resources\ActivitylogResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Rmsramos\Activitylog\Resources\ActivitylogResource;

class ViewActivitylog extends ViewRecord
{
    public static function getResource(): string
    {
        return ActivitylogResource::class;
    }
}
