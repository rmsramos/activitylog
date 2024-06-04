<?php

namespace Rmsramos\Activitylog\Actions;

use Filament\Actions\StaticAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Rmsramos\Activitylog\Infolists\Components\TimeLineIconEntry;
use Rmsramos\Activitylog\Infolists\Components\TimeLinePropertieEntry;
use Rmsramos\Activitylog\Infolists\Components\TimeLineRepeatableEntry;
use Rmsramos\Activitylog\Infolists\Components\TimeLineTitleEntry;
use Spatie\Activitylog\Models\Activity;

class ActivityLogTimelineAction extends Action
{
    private ?array $withRelations = null;

    private ?array $timelineIcons = null;

    private ?array $timelineIconColors = null;

    public static function getDefaultName(): ?string
    {
        return 'activitylog_timeline';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->configureInfolist();
        $this->configureModal();
    }

    private function configureInfolist(): void
    {
        $this->infolist(function (?Model $record, Infolist $infolist) {
            return $infolist
                ->state(['activities' => $this->getActivityLogRecord($record, $this->getWithRelations())])
                ->schema($this->getSchema());
        });
    }

    private function configureModal(): void
    {
        $this->slideOver()
            ->modalHeading(__('activitylog::action.modal.heading'))
            ->modalDescription(__('activitylog::action.modal.description'))
            ->modalIcon('heroicon-o-eye')
            ->modalFooterActions(fn () => [])
            ->tooltip(__('activitylog::action.modal.tooltip'))
            ->icon('heroicon-o-bell-alert');
    }

    private function getSchema(): array
    {
        return [
            TimeLineRepeatableEntry::make('activities')
                ->schema([
                    TimeLineIconEntry::make('activityData.event')
                        ->icon(function ($state) {
                            return $this->getTimelineIcons()[$state] ?? 'heroicon-m-check';
                        })
                        ->color(function ($state) {
                            return $this->getTimelineIconColors()[$state] ?? 'primary';
                        }),
                    TimeLineTitleEntry::make('activityData'),
                    TimeLinePropertieEntry::make('activityData'),
                    TextEntry::make('updated_at')
                        ->hiddenLabel()
                        ->since()
                        ->badge(),
                ]),
        ];
    }

    public function withRelations(?array $relations = null): ?StaticAction
    {
        $this->withRelations = $relations;

        return $this;
    }

    public function getWithRelations(): ?array
    {
        return $this->evaluate($this->withRelations);
    }

    public function timelineIcons(?array $timelineIcons = null): ?StaticAction
    {
        $this->timelineIcons = $timelineIcons;

        return $this;
    }

    public function getTimelineIcons(): ?array
    {
        return $this->evaluate($this->timelineIcons);
    }

    public function timelineIconColors(?array $timelineIconColors = null): ?StaticAction
    {
        $this->timelineIconColors = $timelineIconColors;

        return $this;
    }

    public function getTimelineIconColors(): ?array
    {
        return $this->evaluate($this->timelineIconColors);
    }

    private function getActivities(?Model $record, ?array $relations = null): Collection
    {
        return Activity::query()
            ->where(function (Builder $query) use ($record, $relations) {
                $query->where(function (Builder $q) use ($record) {
                    $q->where('subject_type', $record->getMorphClass())
                        ->where('subject_id', $record->getKey());
                })->when($relations, function (Builder $query, array $relations) use ($record) {
                    foreach ($relations as $relation) {
                        $model = get_class($record->{$relation}()->getRelated());
                        $query->orWhere(function (Builder $q) use ($record, $model, $relation) {
                            $q->where('subject_type', (new $model())->getMorphClass())
                                ->whereIn('subject_id', $record->{$relation}()->pluck('id'));
                        });
                    }
                });
            })
            ->latest()
            ->get();
    }

    private function getActivityLogRecord(?Model $record, ?array $relations = null): Collection
    {
        $activities = $this->getActivities($record, $relations);

        return $activities->transform(function ($activity) {
            $activity->activityData = $this->formatActivityData($activity);

            return $activity;
        });
    }

    private function formatActivityData($activity): array
    {
        return [
            'log_name'    => $activity->log_name,
            'description' => $activity->description,
            'subject'     => $activity->subject,
            'event'       => $activity->event,
            'causer'      => $activity->causer,
            'properties'  => json_decode($activity->properties, true),
            'batch_uuid'  => $activity->batch_uuid,
            'update'      => $activity->updated_at,
        ];
    }

}
