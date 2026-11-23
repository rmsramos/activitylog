<?php

namespace Rmsramos\Activitylog\Infolists\Concerns;

use Closure;
use Illuminate\Support\HtmlString;

trait HasModifyState
{
    protected $state;

    public function modifyState(Closure $callback): static
    {
        $this->state = $callback;

        return $this;
    }

    public function getModifiedState(): null|string|HtmlString
    {
        return $this->evaluate($this->state);
    }

    protected function getCauserName($causer): string
    {
        return $causer->name ?? $causer->first_name ?? $causer->last_name ?? $causer->username ?? trans('activitylog::infolists.components.unknown');
    }

    /**
     * An event without a translation comes back as its own key, so the label is escaped before it reaches the HtmlString.
     */
    protected function getEventLabel(?string $event): string
    {
        return htmlspecialchars((string) __('activitylog::action.event.' . $event));
    }
}
