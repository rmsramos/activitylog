<?php

namespace Rmsramos\Activitylog\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Rmsramos\Activitylog\Resources\ActivitylogResource;

class ActivitylogRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $recordTitleAttribute = 'description';

    public function form(Form $form): Form
    {
        return ActivitylogResource::form($form);
    }

    public function table(Table $table): Table
    {
        return ActivitylogResource::table($table);
    }
}
