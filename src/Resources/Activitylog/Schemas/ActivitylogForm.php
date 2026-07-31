<?php

namespace Rmsramos\Activitylog\Resources\Activitylog\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Rmsramos\Activitylog\ActivitylogPlugin;
use Rmsramos\Activitylog\Resources\Activitylog\ActivitylogResource;
use Spatie\Activitylog\Models\Activity;

class ActivitylogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    Section::make([
                        TextInput::make('causer_id')
                            ->afterStateHydrated(function ($component, ?Model $record) {
                                return $component->state($record?->causer?->name ?? '-');
                            })
                            ->label(__('activitylog::forms.fields.causer.label')),

                        TextInput::make('subject_type')
                            ->afterStateHydrated(function ($component, ?Model $record, $state) {
                                /** @var Activity $record */
                                return $state ? $component->state(Str::of($state)->afterLast('\\')->headline() . ' # ' . $record->subject_id) : $component->state('-');
                            })
                            ->label(__('activitylog::forms.fields.subject_type.label')),

                        Textarea::make('description')
                            ->label(__('activitylog::forms.fields.description.label'))
                            ->rows(2)
                            ->columnSpan('full'),
                    ]),
                    Section::make([
                        TextEntry::make('log_name')
                            ->state(function (?Model $record): string {
                                /** @var Activity $record */
                                return $record?->log_name ? ucwords($record->log_name) : '-';
                            })
                            ->label(__('activitylog::forms.fields.log_name.label')),

                        TextEntry::make('event')
                            ->state(function (?Model $record): string {
                                /** @var Activity $record */
                                return $record?->event ? ucwords(__('activitylog::action.event.' . $record->event)) : '-';
                            })
                            ->label(__('activitylog::forms.fields.event.label')),

                        TextEntry::make('created_at')
                            ->label(__('activitylog::forms.fields.created_at.label'))
                            ->state(function (?Model $record): string {
                                /** @var Activity $record */
                                if (! $record?->created_at) {
                                    return '-';
                                }

                                $parser = ActivitylogPlugin::get()->getDateParser();

                                return $parser($record->created_at)
                                    ->format(ActivitylogPlugin::get()->getDatetimeFormat());
                            }),
                    ])->grow(false),
                ])->from('md'),

                Section::make(__('activitylog::forms.changes'))
                    ->headerActions([
                        Action::make('restore')
                            ->label(__('activitylog::action.restore'))
                            ->icon('heroicon-o-arrow-uturn-left')
                            ->color('primary')
                            ->action(fn (Activity $record) => ActivitylogResource::restoreActivity($record->id))
                            ->visible(function (Activity $record): bool {
                                return ! ActivitylogPlugin::get()->getIsRestoreActionHidden() && $record->properties &&
                                    data_get($record->properties, 'old') !== null &&
                                    $record->subject !== null && $record->event !== 'deleted';
                            })
                            ->authorize(fn () => auth()->user()?->can('restore_activitylog') ?? false)
                            ->requiresConfirmation(),

                        Action::make('edit')
                            ->label(__('activitylog::action.edit'))
                            ->icon('heroicon-o-eye')
                            ->color('info')
                            ->url(fn (Activity $record) => ActivitylogResource::getResourceUrl($record))
                            ->visible(fn () => ! ActivitylogPlugin::get()->getIsResourceActionHidden())
                            ->authorize(fn (Activity $record) => ActivitylogResource::canViewResource($record)),

                        Action::make('restore_soft_delete')
                            ->label(__('activitylog::action.restore_soft_delete.label'))
                            ->icon('heroicon-o-arrow-uturn-left')
                            ->color('warning')
                            ->visible(function (Activity $record): bool {
                                return ActivitylogResource::canRestoreSubjectFromSoftDelete($record);
                            })
                            ->action(function (Activity $record) {
                                ActivitylogResource::restoreSubjectFromSoftDelete($record);
                            })
                            ->authorize(fn () => auth()->user()?->can('restore_activitylog') ?? false)
                            ->requiresConfirmation()
                            ->modalHeading(__('activitylog::action.restore_soft_delete.modal_heading'))
                            ->modalDescription(__('activitylog::action.restore_soft_delete.modal_description')),
                    ])
                    ->columns()
                    ->visible(fn (?Model $record) => $record?->properties?->count() > 0)
                    ->schema(function (?Model $record) {
                        /** @var Activity $record */
                        if (! $record?->properties) {
                            return [];
                        }

                        $properties = $record->properties->except(['attributes', 'old']);
                        $schema     = [];

                        if ($properties->count()) {
                            $schema[] = KeyValue::make('properties')
                                ->afterStateHydrated(function (KeyValue $component) use ($properties) {
                                    $component->state(ActivitylogResource::flattenArrayForKeyValue($properties->toArray()));
                                })
                                ->label(__('activitylog::forms.fields.properties.label'))
                                ->columnSpan('full')
                                ->disabled();
                        }

                        if ($old = $record->properties->get('old')) {
                            $schema[] = KeyValue::make('old')
                                ->afterStateHydrated(function (KeyValue $component) use ($old) {
                                    $oldArray = is_array($old) ? $old : [];
                                    $component->state(ActivitylogResource::flattenArrayForKeyValue($oldArray));
                                })
                                ->label(__('activitylog::forms.fields.old.label'))
                                ->disabled();
                        }

                        if ($attributes = $record->properties->get('attributes')) {
                            $schema[] = KeyValue::make('attributes')
                                ->afterStateHydrated(function (KeyValue $component) use ($attributes) {
                                    $attributesArray = is_array($attributes) ? $attributes : [];
                                    $component->state(ActivitylogResource::flattenArrayForKeyValue($attributesArray));
                                })
                                ->label(__('activitylog::forms.fields.attributes.label'))
                                ->disabled();
                        }

                        return $schema;
                    }),
            ])
            ->columns(1);
    }
}
