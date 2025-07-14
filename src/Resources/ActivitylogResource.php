<?php

namespace Rmsramos\Activitylog\Resources;

use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Component as Livewire;
use Rmsramos\Activitylog\Actions\Concerns\ActionContent;
use Rmsramos\Activitylog\ActivitylogPlugin;
use Rmsramos\Activitylog\Helpers\ActivityLogHelper;
use Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager;
use Rmsramos\Activitylog\Resources\ActivitylogResource\Pages\ListActivitylog;
use Rmsramos\Activitylog\Resources\ActivitylogResource\Pages\ViewActivitylog;
use Rmsramos\Activitylog\Traits\HasCustomActivityResource;

use Spatie\Activitylog\Models\Activity;

class ActivitylogResource extends Resource
{
    use ActionContent;

    protected static ?string $slug = 'activitylogs';

    public static function getModel(): string
    {
        return config('activitylog.activity_model', Activity::class);
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

    protected static function getResourceUrl(Activity $record): string
    {
        $panelID = Filament::getCurrentPanel()->getId();

        if ($record->subject_type && $record->subject_id) {
            try {
                $model = app($record->subject_type);

                if (ActivityLogHelper::classUsesTrait($model, HasCustomActivityResource::class)) {
                    $resourceModel      = $model->getFilamentActualResourceModel($record);
                    $resourcePluralName = ActivityLogHelper::getResourcePluralName($resourceModel);

                    return route('filament.' . $panelID . '.resources.' . $resourcePluralName . '.edit', ['record' => $resourceModel->id]);
                }

                // Fallback to a standard resource mapping
                $resourcePluralName = ActivityLogHelper::getResourcePluralName($record->subject_type);

                return route('filament.' . $panelID . '.resources.' . $resourcePluralName . '.edit', ['record' => $record->subject_id]);
            } catch (Exception $e) {
                // If there's any error generating the URL, return placeholder
                return '#';
            }
        }

        return '#';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
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
                        Placeholder::make('log_name')
                            ->content(function (?Model $record): string {
                                /** @var Activity $record */
                                return $record?->log_name ? ucwords($record->log_name) : '-';
                            })
                            ->label(__('activitylog::forms.fields.log_name.label')),

                        Placeholder::make('event')
                            ->content(function (?Model $record): string {
                                /** @var Activity $record */
                                return $record?->event ? ucwords(__('activitylog::action.event.' . $record->event)) : '-';
                            })
                            ->label(__('activitylog::forms.fields.event.label')),

                        Placeholder::make('created_at')
                            ->label(__('activitylog::forms.fields.created_at.label'))
                            ->content(function (?Model $record): string {
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
                            ->action(fn (Activity $record) => self::restoreActivity($record->id))
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
                            ->url(fn (Activity $record) => self::getResourceUrl($record))
                            ->visible(fn () => ! ActivitylogPlugin::get()->getIsResourceActionHidden())
                            ->authorize(fn (Activity $record) => self::canViewResource($record)),

                        Action::make('restore_soft_delete')
                            ->label(__('activitylog::action.restore_soft_delete.label'))
                            ->icon('heroicon-o-arrow-uturn-left')
                            ->color('warning')
                            ->visible(function (Activity $record): bool {
                                return static::canRestoreSubjectFromSoftDelete($record);
                            })
                            ->action(function (Activity $record) {
                                static::restoreSubjectFromSoftDelete($record);
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
                                    $component->state(static::flattenArrayForKeyValue($properties->toArray()));
                                })
                                ->label(__('activitylog::forms.fields.properties.label'))
                                ->columnSpan('full')
                                ->disabled();
                        }

                        if ($old = $record->properties->get('old')) {
                            $schema[] = KeyValue::make('old')
                                ->afterStateHydrated(function (KeyValue $component) use ($old) {
                                    $oldArray = is_array($old) ? $old : [];
                                    $component->state(static::flattenArrayForKeyValue($oldArray));
                                })
                                ->label(__('activitylog::forms.fields.old.label'))
                                ->disabled();
                        }

                        if ($attributes = $record->properties->get('attributes')) {
                            $schema[] = KeyValue::make('attributes')
                                ->afterStateHydrated(function (KeyValue $component) use ($attributes) {
                                    $attributesArray = is_array($attributes) ? $attributes : [];
                                    $component->state(static::flattenArrayForKeyValue($attributesArray));
                                })
                                ->label(__('activitylog::forms.fields.attributes.label'))
                                ->disabled();
                        }

                        return $schema;
                    }),
            ])->columns(1);
    }

    protected static function flattenArrayForKeyValue(array $data): array
    {
        $flattened = [];

        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $flattened[$key] = json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
            } else {
                $flattened[$key] = $value;
            }
        }

        return $flattened;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                static::getLogNameColumnComponent(),
                static::getEventColumnComponent(),
                static::getSubjectTypeColumnComponent(),
                static::getCauserNameColumnComponent(),
                static::getPropertiesColumnComponent(),
                static::getCreatedAtColumnComponent(),
            ])
            ->defaultSort(
                config('filament-activitylog.resources.default_sort_column', 'created_at'),
                config('filament-activitylog.resources.default_sort_direction', 'desc')
            )
            ->filters([
                static::getDateFilterComponent(),
                static::getEventFilterComponent(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'subject' => function ($query) {
                    if (method_exists($query, 'withTrashed')) {
                        $query->withTrashed();
                    }
                },
                'causer',
            ]);
    }

    public static function getLogNameColumnComponent(): Column
    {
        return TextColumn::make('log_name')
            ->label(__('activitylog::tables.columns.log_name.label'))
            ->formatStateUsing(fn ($state) => $state ? ucwords($state) : '-')
            ->searchable()
            ->sortable()
            ->badge();
    }

    public static function getEventColumnComponent(): Column
    {
        return TextColumn::make('event')
            ->label(__('activitylog::tables.columns.event.label'))
            ->formatStateUsing(fn ($state) => $state ? ucwords(__('activitylog::action.event.' . $state)) : '-')
            ->badge()
            ->color(fn (?string $state): string => match ($state) {
                'draft'    => 'gray',
                'updated'  => 'warning',
                'created'  => 'success',
                'deleted'  => 'danger',
                'restored' => 'info',
                default    => 'primary',
            })
            ->searchable()
            ->sortable();
    }

    public static function getSubjectTypeColumnComponent(): Column
    {
        return TextColumn::make('subject_type')
            ->label(__('activitylog::tables.columns.subject_type.label'))
            ->formatStateUsing(function ($state, Model $record) {
                /** @var Activity $record */
                if (! $state) {
                    return '-';
                }

                $subjectInfo = Str::of($state)->afterLast('\\')->headline() . ' # ' . $record->subject_id;

                if ($record->subject) {
                    if (method_exists($record->subject, 'trashed') && $record->subject->trashed()) {
                        $subjectInfo .= __('activitylog::tables.columns.subject_type.soft_deleted');
                    }
                } else {
                    $subjectInfo .= __('activitylog::tables.columns.subject_type.deleted');
                }

                return $subjectInfo;
            })
            ->searchable()
            ->hidden(fn (Livewire $livewire) => $livewire instanceof ActivitylogRelationManager);
    }

    public static function getCauserNameColumnComponent(): Column
    {
        return TextColumn::make('causer.name')
            ->label(__('activitylog::tables.columns.causer.label'))
            ->getStateUsing(function (Model $record) {
                /** @var Activity $record */
                if ($record->causer_id === null || $record->causer === null) {
                    return new HtmlString('&mdash;');
                }

                return $record->causer->name ?? new HtmlString('&mdash;');
            })
            ->searchable();
    }

    public static function getPropertiesColumnComponent(): Column
    {
        return ViewColumn::make('properties')
            ->searchable()
            ->label(__('activitylog::tables.columns.properties.label'))
            ->view('activitylog::filament.tables.columns.activity-logs-properties')
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getCreatedAtColumnComponent(): Column
    {
        $column = TextColumn::make('created_at')
            ->label(__('activitylog::tables.columns.created_at.label'))
            ->dateTime(ActivitylogPlugin::get()->getDatetimeFormat())
            ->searchable()
            ->sortable();

        // Apply the custom callback if set
        $callback = ActivitylogPlugin::get()->getDatetimeColumnCallback();

        if ($callback) {
            $column = $callback($column);
        }

        return $column;
    }

    public static function getDatePickerCompoment(string $label): DatePicker
    {
        $field = DatePicker::make($label)
            ->format(ActivitylogPlugin::get()->getDateFormat())
            ->label(__('activitylog::tables.filters.created_at.' . $label));

        // Apply the custom callback if set
        $callback = ActivitylogPlugin::get()->getDatePickerCallback();

        if ($callback) {
            $field = $callback($field);
        }

        return $field;
    }

    public static function getDateFilterComponent(): Filter
    {
        return Filter::make('created_at')
            ->label(__('activitylog::tables.filters.created_at.label'))
            ->indicateUsing(function (array $data): array {
                $indicators = [];
                $parser     = ActivitylogPlugin::get()->getDateParser();

                if ($data['created_from'] ?? null) {
                    $indicators['created_from'] = __('activitylog::tables.filters.created_at.created_from_indicator', [
                        'created_from' => $parser($data['created_from'])
                            ->format(ActivitylogPlugin::get()->getDateFormat()),
                    ]);
                }

                if ($data['created_until'] ?? null) {
                    $indicators['created_until'] = __('activitylog::tables.filters.created_at.created_until_indicator', [
                        'created_until' => $parser($data['created_until'])
                            ->format(ActivitylogPlugin::get()->getDateFormat()),
                    ]);
                }

                return $indicators;
            })
            ->form([
                self::getDatePickerCompoment('created_from'),
                self::getDatePickerCompoment('created_until'),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['created_from'] ?? null,
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                    )
                    ->when(
                        $data['created_until'] ?? null,
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                    );
            });
    }

    public static function getEventFilterComponent(): SelectFilter
    {
        return SelectFilter::make('event')
            ->label(__('activitylog::tables.filters.event.label'))
            ->options(static::getModel()::distinct()
                ->pluck('event', 'event')
                ->mapWithKeys(fn ($value, $key) => [$key => __('activitylog::action.event.' . $value)])
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivitylog::route('/'),
            'view'  => ViewActivitylog::route('/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('rmsramos/activitylog');

        return $plugin?->getNavigationItem() ?? false;
    }

    public static function canAccess(): bool
    {
        $policy = Gate::getPolicyFor(static::getModel());

        if ($policy && method_exists($policy, 'viewAny')) {
            return static::canViewAny();
        }

        return ActivitylogPlugin::get()->isAuthorized();
    }

    protected static function canViewResource(Activity $record): bool
    {
        if ($record->subject_type && $record->subject_id) {
            try {
                $model = app($record->subject_type);

                if (ActivityLogHelper::classUsesTrait($model, HasCustomActivityResource::class)) {
                    $resourceModel = $model->getFilamentActualResourceModel($record);
                    $user          = auth()->user();

                    return $user && $user->can('update', $resourceModel);
                }

                // Fallback to check if the user can edit the model using a generic policy
                $user = auth()->user();

                return $user && $record->subject && $user->can('update', $record->subject);
            } catch (Exception $e) {
                return false;
            }
        }

        return false;
    }

    public static function restoreActivity(int|string $key): void
    {
        $activity = Activity::find($key);

        if (! $activity) {
            Notification::make()
                ->title(__('activitylog::notifications.activity_not_found'))
                ->danger()
                ->send();

            return;
        }

        $oldProperties = data_get($activity, 'properties.old');
        $newProperties = data_get($activity, 'properties.attributes');

        if ($oldProperties === null) {
            Notification::make()
                ->title(__('activitylog::notifications.no_properties_to_restore'))
                ->danger()
                ->send();

            return;
        }

        try {
            $record = $activity->subject;

            if (! $record) {
                Notification::make()
                    ->title(__('activitylog::notifications.subject_not_found'))
                    ->danger()
                    ->send();

                return;
            }

            // Temporarily disable activity logging to prevent updated log
            activity()->withoutLogs(function () use ($record, $oldProperties) {
                $record->update($oldProperties);
            });

            if (auth()->user()) {
                activity()
                    ->performedOn($record)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'attributes' => $oldProperties,
                        'old'        => $newProperties,
                    ])
                    ->tap(function ($log) {
                        $log->event = 'restored';
                    })
                    ->log('restored');
            }

            Notification::make()
                ->title(__('activitylog::notifications.activity_restored_successfully'))
                ->success()
                ->send();
        } catch (ModelNotFoundException $e) {
            Notification::make()
                ->title(__('activitylog::notifications.record_not_found'))
                ->danger()
                ->send();
        } catch (Exception $e) {
            Notification::make()
                ->title(__('activitylog::notifications.failed_to_restore_activity', ['error' => $e->getMessage()]))
                ->danger()
                ->send();
        }
    }

    public static function canRestoreSubjectFromSoftDelete(Activity $record): bool
    {
        if (ActivitylogPlugin::get()->getIsRestoreModelActionHidden()) {
            return false;
        }

        if ($record->event !== 'deleted') {
            return false;
        }

        if (! $record->subject) {
            return false;
        }

        if (! method_exists($record->subject, 'trashed') ||
            ! method_exists($record->subject, 'restore')) {
            return false;
        }

        if (! $record->subject->trashed()) {
            return false;
        }

        $user = auth()->user();

        if ($user && method_exists($record->subject, 'exists')) {
            try {
                return $user->can('restore', $record->subject);
            } catch (\Exception $e) {
                return true;
            }
        }

        return true;
    }

    public static function restoreSubjectFromSoftDelete(Activity $record): void
    {
        if (! static::canRestoreSubjectFromSoftDelete($record)) {
            Notification::make()
                ->title(__('activitylog::notifications.unable_to_restore_this_model'))
                ->danger()
                ->send();

            return;
        }

        try {
            DB::beginTransaction();

            $subject = $record->subject;

            $beforeRestore = $subject->toArray();

            activity()->withoutLogs(function () use ($subject) {
                $subject->restore();
            });

            $subject->refresh();
            $afterRestore = $subject->toArray();

            if (auth()->user()) {
                activity()
                    ->performedOn($subject)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'attributes'       => $afterRestore,
                        'old'              => $beforeRestore,
                        'restore_metadata' => [
                            'restored_from_soft_delete' => true,
                            'original_activity_id'      => $record->id,
                            'restore_type'              => 'soft_delete',
                        ],
                    ])
                    ->tap(function ($log) {
                        $log->event = 'restored';
                    })
                    ->log('restored');
            }

            DB::commit();

            Notification::make()
                ->title(__('activitylog::notifications.model_successfully_restored'))
                ->success()
                ->send();

        } catch (Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title(__('activitylog::notifications.error_restoring_model'))
                ->body('Erro: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
