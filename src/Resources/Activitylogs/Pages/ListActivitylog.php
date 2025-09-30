<?php

namespace Rmsramos\Activitylog\Resources\Activitylogs\Pages;

use Filament\Resources\Pages\ListRecords;
use Rmsramos\Activitylog\Resources\Activitylogs\ActivitylogResource;

class ListActivitylog extends ListRecords
{
    protected static string $resource = ActivitylogResource::class;
}
