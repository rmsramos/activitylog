<?php

namespace Rmsramos\Activitylog\Resources\Activitylog\Pages;

use Filament\Resources\Pages\ListRecords;
use Rmsramos\Activitylog\Resources\Activitylog\ActivitylogResource;

class ListActivitylog extends ListRecords
{
    protected static string $resource = ActivitylogResource::class;
}
