<?php

namespace Rmsramos\Activitylog\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Rmsramos\Activitylog\ActivitylogPlugin;
use Rmsramos\Activitylog\Resources\ActivitylogResource\Pages\ListActivitylog;
use Spatie\Activitylog\Models\Activity;

class ActivitylogResource extends Resource
{
    public static function getModel(): string
    {
        return Activity::class;
    }

    public static function getModelLabel(): string
    {
        return ActivitylogPlugin::get()->getLabel();
    }

    public static function getPluralModelLabel(): string
    {
        return ActivitylogPlugin::get()->getPluralLabel();
    }

    public static function getNavigationIcon(): string
    {
        return ActivitylogPlugin::get()->getNavigationIcon();
    }

    public static function getNavigationLabel(): string
    {
        return Str::title(static::getPluralModelLabel()) ?? Str::title(static::getModelLabel());
    }

    public static function getNavigationSort(): ?int
    {
        return ActivitylogPlugin::get()->getNavigationSort();
    }

    public static function getNavigationGroup(): ?string
    {
        return ActivitylogPlugin::get()->getNavigationGroup();
    }

    public static function getNavigationBadge(): ?string
    {
        return ActivitylogPlugin::get()->getNavigationCountBadge() ?
            number_format(static::getModel()::count()) : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('log_name'),
                TextColumn::make('description'),
                TextColumn::make('subject_type'),
                TextColumn::make('subject_id'),
                TextColumn::make('causer_type'),
                TextColumn::make('causer_id'),
                ViewColumn::make('properties')
                    ->view('activitylog::filament.tables.columns.activity-logs-properties'),
                TextColumn::make('created_at')
                    ->dateTime(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivitylog::route('/'),
        ];
    }
}
