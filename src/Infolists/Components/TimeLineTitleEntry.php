<?php

namespace Rmsramos\Activitylog\Infolists\Components;

use Closure;
use Filament\Forms\Components\Concerns\CanAllowHtml;
use Filament\Infolists\Components\Entry;
use Filament\Support\Concerns\HasExtraAttributes;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Rmsramos\Activitylog\ActivitylogPlugin;
use Rmsramos\Activitylog\Infolists\Concerns\HasModifyState;

class TimeLineTitleEntry extends Entry
{
    use CanAllowHtml;
    use HasExtraAttributes;
    use HasModifyState;

    protected ?Closure $configureTitleUsing = null;

    protected ?Closure $shouldConfigureTitleUsing = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configureTitleEntry();
    }

    protected string $view = 'activitylog::filament.infolists.components.time-line-title-entry';

    public function configureTitleUsing(?Closure $configureTitleUsing): TimeLineTitleEntry
    {
        $this->configureTitleUsing = $configureTitleUsing;

        return $this;
    }

    public function shouldConfigureTitleUsing(?Closure $condition): TimeLineTitleEntry
    {
        $this->shouldConfigureTitleUsing = $condition;

        return $this;
    }

    protected function configureTitleEntry()
    {
        $this
            ->hiddenLabel()
            ->modifyState(fn ($state) => $this->modifiedTitle($state));
    }

    protected function modifiedTitle($state): string|HtmlString|Closure
    {
        if ($this->configureTitleUsing !== null && $this->shouldConfigureTitleUsing !== null && $this->evaluate($this->shouldConfigureTitleUsing)) {
            return $this->evaluate($this->configureTitleUsing, ['state' => $state]);
        } else {
            if ($state['description'] == $state['event'] || $state['event'] === 'restored') {
                $className = property_exists($state['subject'], 'activityTitleName') && ! empty($state['subject']::$activityTitleName)
                    ? $state['subject']::$activityTitleName
                    : Str::lower(Str::snake(class_basename($state['subject']), ' '));
                $causerName = $this->getCauserName($state['causer']);

                $parser    = ActivitylogPlugin::get()->getDateParser();
                $update_at = $parser($state['update'])->format(ActivitylogPlugin::get()->getDatetimeFormat());

                return new HtmlString(
                    __('activitylog::infolists.components.created_by_at',
                        [
                            'subject'   => ActivitylogPlugin::get()->getTranslateSubject($className),
                            'event'     => __('activitylog::action.event.' . $state['event']),
                            'causer'    => $causerName,
                            'update_at' => $update_at,
                        ]),
                );
            }
        }

        return '';
    }
}
