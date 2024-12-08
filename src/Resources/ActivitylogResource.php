<?php

namespace Rmsramos\Activitylog\Resources;

use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Component as Livewire;
use Rmsramos\Activitylog\Actions\Concerns\ActionContent;
use Rmsramos\Activitylog\ActivitylogPlugin;
use Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager;
use Rmsramos\Activitylog\Resources\ActivitylogResource\Pages\ListActivitylog;
use Rmsramos\Activitylog\Resources\ActivitylogResource\Pages\ViewActivitylog;
use Spatie\Activitylog\Models\Activity;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use Rmsramos\Activitylog\Helpers\ActivityLogHelper;
use Rmsramos\Activitylog\Traits\HasCustomActivityResource;


class ActivitylogResource extends Resource
{
    use ActionContent;

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

    public static function restoreActivity(int|string $key): void
    {
        $activity = Activity::find($key);

        $oldProperties = data_get($activity, 'properties.old');

        $newProperties = data_get($activity, 'properties.attributes');

        if ($oldProperties === null) {
            Notification::make()
                ->title(__("activitylog::notifications.no_properties_to_restore"))
                ->danger()
                ->send();
            return;
        }

        try {
            $record = $activity->subject;

            // Temporarily disable activity logging to prevent updated log
            activity()->withoutLogs(function () use ($record, $oldProperties) {
                $record->update($oldProperties);
            });

            // Log the restore event
            activity()
                ->performedOn($record)
                ->causedBy(auth()->user())
                ->withProperties(["attributes"=>$oldProperties, "old" => $newProperties])
                ->tap(function ($log) {
                    $log->event = 'restored';
                })
                ->log('restored');

            Notification::make()
                ->title(__("activitylog::notifications.activity_restored_successfully"))
                ->success()
                ->send();
        } catch (Exception $e) {
            Notification::make()
                ->title(__("activitylog::notifications.failed_to_restore_activity",["error" => $e->getMessage()]))
                ->danger()
                ->send();
        }
    }

    private static function getResourceUrl($record)
    {
        $panelID = Filament::getCurrentPanel()->getId();

        if ($record->subject_type && $record->subject_id) {
            $model = app($record->subject_type);
            if (ActivityLogHelper::classUsesTrait($model, HasCustomActivityResource::class)) {
                $resourceModel = $model->getFilamentActualResourceModel($record);
                $resourcePluralName = ActivityLogHelper::getResourcePluralName($resourceModel);
                return route('filament.'.$panelID.'.resources.' . $resourcePluralName . '.edit', ['record' => $resourceModel->id]);
            } 
            
            // Fallback to a standard resource mapping
            $resourcePluralName = ActivityLogHelper::getResourcePluralName($record->subject_type);
            return route('filament.'.$panelID.'.resources.' . $resourcePluralName . '.edit', ['record' => $record->subject_id]);
        }

        return '#';
    }

    private static function canViewResource($record)
    {
        return true;
        if ($record->subject_type && $record->subject_id) {
            $model = app($record->subject_type);
            if (ActivityLogHelper::classUsesTrait($model, HasCustomActivityResource::class)) {
                $resourceModel = $model->getFilamentActualResourceModel($record);
                return auth()->user()->can('update', $resourceModel);
            } 
            
            // Fallback to check if the user can edit the model using a generic policy
            return auth()->user()->can('update', $record->subject);
        }

        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make([
                        TextInput::make('causer_id')
                            ->afterStateHydrated(function ($component, ?Model $record) {
                                /** @phpstan-ignore-next-line */
                                return $component->state($record->causer?->name);
                            })
                            ->label(__('activitylog::forms.fields.causer.label')),

                        TextInput::make('subject_type')
                            ->afterStateHydrated(function ($component, ?Model $record, $state) {
                                /** @var Activity&ActivityModel $record */
                                return $state ? $component->state(Str::of($state)->afterLast('\\')->headline() . ' # ' . $record->subject_id) : '-';
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
                                /** @var Activity&ActivityModel $record */
                                return $record->log_name ? ucwords($record->log_name) : '-';
                            })
                            ->label(__('activitylog::forms.fields.log_name.label')),

                        Placeholder::make('event')
                            ->content(function (?Model $record): string {
                                /** @phpstan-ignore-next-line */
                                return $record?->event ? ucwords(__('activitylog::action.event.'.$record?->event)) : '-';
                            })
                            ->label(__('activitylog::forms.fields.event.label')),

                        Placeholder::make('created_at')
                            ->label(__('activitylog::forms.fields.created_at.label'))
                            ->content(function (?Model $record): string {
                                /** @var Activity&ActivityModel $record */

                                $parser = ActivitylogPlugin::get()->getDateParser();

                                return $record->created_at ? 
                                    $parser($record->created_at)
                                        ->format(ActivitylogPlugin::get()->getDatetimeFormat()) 
                                    : '-';
                            }),
                    ])->grow(false),
                ])->from('md'),

                Section::make(__('activitylog::forms.changes'))
                    ->headerActions([
                        Action::make(__('activitylog::action.restore'))
                            ->icon('heroicon-o-eye')
                            ->color('primary')
                            ->action(fn (Activity $record) => self::restoreActivity($record->id))
                            ->visible(fn () => !ActivitylogPlugin::get()->getIsRestoreActionHidden() ?? true)
                            ->authorize(fn () => auth()->user()->can("restore_activitylog"))
                            ->requiresConfirmation(),
                        Action::make(__('activitylog::action.edit'))
                            ->label( ActivitylogPlugin::get()->getResourceActionLabel() ?? __('activitylog::action.edit'))
                            ->icon('heroicon-o-eye')
                            ->color('info')
                            ->url(fn ($record) => self::getResourceUrl($record))
                            ->visible(fn () => !ActivitylogPlugin::get()->getIsResourceActionHidden() ?? true)
                            ->authorize(fn ($record) => self::canViewResource($record))
                            ,
                    ])
                    ->columns()
                    ->visible(fn ($record) => $record->properties?->count() > 0)
                    ->schema(function (?Model $record) {
                        /** @var Activity&ActivityModel $record */
                        $properties = $record->properties->except(['attributes', 'old']);

                        $schema = [];

                        if ($properties->count()) {
                            $schema[] = KeyValue::make('properties')
                                ->label(__('activitylog::forms.fields.properties.label'))
                                ->columnSpan('full');
                        }

                        if ($old = $record->properties->get('old')) {
                            $schema[] = KeyValue::make('old')
                                ->formatStateUsing(fn () => self::formatDateValues($old))
                                ->label(__('activitylog::forms.fields.old.label'));
                        }

                        if ($attributes = $record->properties->get('attributes')) {
                            $schema[] = KeyValue::make('attributes')
                                ->formatStateUsing(fn () => self::formatDateValues($attributes))
                                ->label(__('activitylog::forms.fields.attributes.label'));
                        }

                        return $schema;
                    }),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                static::getLogNameColumnCompoment(),
                static::getEventColumnCompoment(),
                static::getSubjectTypeColumnCompoment(),
                static::getCauserNameColumnCompoment(),
                static::getPropertiesColumnCompoment(),
                static::getCreatedAtColumnCompoment(),
            ])
            ->defaultSort(config('filament-activitylog.resources.default_sort_column', 'created_at'), config('filament-activitylog.resources.default_sort_direction', 'asc'))
            ->filters([
                static::getDateFilterComponent(),
                static::getEventFilterCompoment(),
            ]);
    }

    public static function getLogNameColumnCompoment(): Column
    {
        return TextColumn::make('log_name')
            ->label(__('activitylog::tables.columns.log_name.label'))
            ->badge()
            ->formatStateUsing(fn ($state) => ucwords($state))
            ->sortable();
    }

    public static function getEventColumnCompoment(): Column
    {
        return TextColumn::make('event')
            ->label(__('activitylog::tables.columns.event.label'))
            ->formatStateUsing(fn ($state) => ucwords(__("activitylog::action.event.".$state)))
            ->badge()
            ->color(fn (string $state): string => match ($state) {
                'draft'   => 'gray',
                'updated' => 'warning',
                'created' => 'success',
                'deleted' => 'danger',
                'restored'=> 'info',
                default   => 'primary',
            })
            ->sortable();
    }

    public static function getSubjectTypeColumnCompoment(): Column
    {
        return TextColumn::make('subject_type')
            ->label(__('activitylog::tables.columns.subject_type.label'))
            ->formatStateUsing(function ($state, Model $record) {
                /** @var Activity&ActivityModel $record */
                if (! $state) {
                    return '-';
                }

                return Str::of($state)->afterLast('\\')->headline() . ' # ' . $record->subject_id;
            })
            ->hidden(fn (Livewire $livewire) => $livewire instanceof ActivitylogRelationManager);
    }

    public static function getCauserNameColumnCompoment(): Column
    {
        return TextColumn::make('causer.name')
            ->label(__('activitylog::tables.columns.causer.label'))
            ->getStateUsing(function (Model $record) {

                if ($record->causer_id == null) {
                    return new HtmlString('&mdash;');
                }

                return $record->causer->name;
            })
            ->searchable();
    }

    public static function getPropertiesColumnCompoment(): Column
    {
        return ViewColumn::make('properties')
            ->label(__('activitylog::tables.columns.properties.label'))
            ->view('activitylog::filament.tables.columns.activity-logs-properties')
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getCreatedAtColumnCompoment(): Column
    {
        $column = TextColumn::make('created_at')
            ->label(__('activitylog::tables.columns.created_at.label'))
            ->dateTime(ActivitylogPlugin::get()->getDatetimeFormat())
            ->sortable();

        // Apply the custom callback if set
        $callback = ActivitylogPlugin::get()->getDatetimeColumnCallback();
        if ($callback) {
            $column = $callback($column);
        }

        return $column;
    }

    public static function getDateFilterComponent(): Filter
    {
        return Filter::make('created_at')
            ->label(__('activitylog::tables.filters.created_at.label'))
            ->indicateUsing(function (array $data): array {
                $indicators = [];
                $parser = ActivitylogPlugin::get()->getDateParser();

                if ($data['created_from'] ?? null) {
                    $indicators['created_from'] = __('activitylog::tables.filters.created_at.created_from_indicator', 
                        [
                            "created_from" => $parser($data['created_from'])
                                                    ->format(ActivitylogPlugin::get()->getDateFormat())
                        ]);
                }

                if ($data['created_until'] ?? null) {
                    $indicators['created_until'] = __('activitylog::tables.filters.created_at.created_until_indicator', 
                        [
                            "created_until" => $parser($data['created_until'])
                                                    ->format(ActivitylogPlugin::get()->getDateFormat())
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
                        $data['created_from'],
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                    )
                    ->when(
                        $data['created_until'],
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                    );
            });
    }

    public static function getDatePickerCompoment($label): DatePicker
    {
        $field = DatePicker::make($label)
                    ->format(ActivitylogPlugin::get()->getDateFormat())
                    ->label(__('activitylog::tables.filters.created_at.'.$label));

        // Apply the custom callback if set
        $callback = ActivitylogPlugin::get()->getDatePickerCallback();
        if ($callback) {
            $field = $callback($field);
        }

        return $field;
    }

    public static function getEventFilterCompoment(): SelectFilter
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

        return $plugin->getNavigationItem();
    }

    public static function canAccess(): bool
    {
        $policy = Gate::getPolicyFor(static::getModel());

        if ($policy && method_exists($policy, 'viewAny')) {
            return static::canViewAny();
        } else {
            return ActivitylogPlugin::get()->isAuthorized();
        }
    }
}
