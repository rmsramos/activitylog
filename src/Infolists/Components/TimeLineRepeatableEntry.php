<?php

namespace Rmsramos\Activitylog\Infolists\Components;

use Filament\Infolists\Components\RepeatableEntry;

class TimeLineRepeatableEntry extends RepeatableEntry
{
    protected function setup(): void
    {
        parent::setup();

        $this->configureRepeatableEntry();
    }

    private function configureRepeatableEntry(): void
    {
        $this
            ->extraAttributes(['style' => 'margin-left:1.25rem;'])
            ->contained(false)
            ->hiddenLabel();
    }

    protected string $view = 'activitylog::filament.infolists.components.time-line-repeatable-entry';
}
