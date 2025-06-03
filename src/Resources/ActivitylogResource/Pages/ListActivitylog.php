<?php

namespace Rmsramos\Activitylog\Resources\ActivitylogResource\Pages;

use Filament\Resources\Pages\ListRecords;

class ListActivitylog extends ListRecords
{
    public static function getResource(): string
    {
        return config('filament-activitylog.resources.resource');
    }
}
