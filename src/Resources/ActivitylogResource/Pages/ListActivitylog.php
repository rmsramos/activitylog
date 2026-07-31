<?php

namespace Rmsramos\Activitylog\Resources\ActivitylogResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Rmsramos\Activitylog\Resources\ActivitylogResource\ActivitylogResource;

class ListActivitylog extends ListRecords
{
    protected static string $resource = ActivitylogResource::class;
}
