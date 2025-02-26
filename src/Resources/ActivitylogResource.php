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
                                return $record?->event ? ucwords($record?->event) : '-';
                            })
                            ->label(__('activitylog::forms.fields.event.label')),

                        Placeholder::make('created_at')
                            ->label(__('activitylog::forms.fields.created_at.label'))
                            ->content(function (?Model $record): string {
                                /** @var Activity&ActivityModel $record */
                                return $record->created_at ? "{$record->created_at->format(config('filament-activitylog.datetime_format', 'd/m/Y H:i:s'))}" : '-';
                            }),
                    ])->grow(false),
                ])->from('md'),

                Section::make()
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
                static::getLogNameColumnComponent(),
                static::getEventColumnComponent(),
                static::getSubjectTypeColumnComponent(),
                static::getCauserNameColumnComponent(),
                static::getPropertiesColumnComponent(),
                static::getCreatedAtColumnComponent(),
            ])
            ->defaultSort(config('filament-activitylog.resources.default_sort_column', 'created_at'), config('filament-activitylog.resources.default_sort_direction', 'asc'))
            ->filters([
                static::getDateFilterComponent(),
                static::getEventFilterComponent(),
            ]);
    }

    public static function getLogNameColumnComponent(): Column
    {
        return TextColumn::make('log_name')
            ->label(__('activitylog::tables.columns.log_name.label'))
            ->formatStateUsing(fn ($state) => ucwords($state))
            ->searchable()
            ->sortable()
            ->badge();
    }

    public static function getEventColumnComponent(): Column
    {
        return TextColumn::make('event')
            ->label(__('activitylog::tables.columns.event.label'))
            ->formatStateUsing(fn ($state) => ucwords($state))
            ->badge()
            ->color(fn (string $state): string => match ($state) {
                'draft'   => 'gray',
                'updated' => 'warning',
                'created' => 'success',
                'deleted' => 'danger',
                default   => 'primary',
            })
            ->searchable()
            ->sortable();
    }

    public static function getSubjectTypeColumnComponent(): Column
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
            ->searchable()
            ->hidden(fn (Livewire $livewire) => $livewire instanceof ActivitylogRelationManager);
    }

    public static function getCauserNameColumnComponent(): Column
    {
        return TextColumn::make('causer.name')
            ->label(__('activitylog::tables.columns.causer.label'))
            ->getStateUsing(function (Model $record) {
                // Check if causer is null or causer_id is null
                if ($record->causer_id == null || $record->causer == null) {
                    return new HtmlString('&mdash;');
                }

                // Return the causer's name if causer exists
                return $record->causer->name;
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
        return TextColumn::make('created_at')
            ->label(__('activitylog::tables.columns.created_at.label'))
            ->dateTime(config('filament-activitylog.datetime_format', 'd/m/Y H:i:s'))
            ->searchable()
            ->sortable();
    }

    public static function getDateFilterComponent(): Filter
    {
        return Filter::make('created_at')
            ->label(__('activitylog::tables.filters.created_at.label'))
            ->indicateUsing(function (array $data): array {
                $indicators = [];

                if ($data['created_from'] ?? null) {
                    $indicators['created_from'] = __('activitylog::tables.filters.created_at.created_from') . Carbon::parse($data['created_from'])->toFormattedDateString();
                }

                if ($data['created_until'] ?? null) {
                    $indicators['created_until'] = __('activitylog::tables.filters.created_at.created_until') . Carbon::parse($data['created_until'])->toFormattedDateString();
                }

                return $indicators;
            })
            ->form([
                DatePicker::make('created_from')
                    ->label(__('activitylog::tables.filters.created_at.created_from')),
                DatePicker::make('created_until')
                    ->label(__('activitylog::tables.filters.created_at.created_until')),
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

    public static function getEventFilterComponent(): SelectFilter
    {
        return SelectFilter::make('event')
            ->label(__('activitylog::tables.filters.event.label'))
            ->options(static::getModel()::distinct()->pluck('event', 'event')->filter());
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
