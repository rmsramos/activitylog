<?php

namespace Rmsramos\Activitylog\Infolists\Components;

use Filament\Infolists\Components\Entry;
use Illuminate\Support\HtmlString;
use Rmsramos\Activitylog\Infolists\Concerns\HasModifyState;

class TimeLinePropertiesEntry extends Entry
{
    use HasModifyState;

    protected string $view = 'activitylog::filament.infolists.components.time-line-propertie-entry';

    protected function setup(): void
    {
        parent::setup();

        $this->configurePropertieEntry();
    }

    private function configurePropertieEntry(): void
    {
        $this
            ->hiddenLabel()
            ->modifyState(fn ($state) => $this->modifiedProperties($state));
    }

    private function modifiedProperties($state): ?HtmlString
    {
        $properties = $state['properties'];

        if (! empty($properties)) {
            $changes    = $this->getPropertyChanges($properties);
            $causerName = $this->getCauserName($state['causer']);

            return new HtmlString(sprintf('%s %s the following: <br>%s', $causerName, $state['event'], implode('<br>', $changes)));
        }

        return null;
    }

    private function getPropertyChanges(array $properties): array
    {
        $changes = [];

        if (isset($properties['old'], $properties['attributes'])) {
            $changes = $this->compareOldAndNewValues($properties['old'], $properties['attributes']);
        } elseif (isset($properties['attributes'])) {
            $changes = $this->getNewValues($properties['attributes']);
        }

        return $changes;
    }

    private function compareOldAndNewValues(array $oldValues, array $newValues): array
    {
        $changes = [];

        foreach ($newValues as $key => $newValue) {
            $oldValue = is_array($oldValues[$key]) ? json_encode($oldValues[$key]) : $oldValues[$key] ?? '-';
            $newValue = $this->formatNewValue($newValue);

            if (isset($oldValues[$key]) && $oldValues[$key] != $newValue) {
                $changes[] = "- {$key} from <strong>" . htmlspecialchars($oldValue) . '</strong> to <strong>' . htmlspecialchars($newValue) . '</strong>';
            } else {
                $changes[] = "- {$key} <strong>" . htmlspecialchars($newValue) . '</strong>';
            }
        }

        return $changes;
    }

    private function getNewValues(array $newValues): array
    {
        return array_map(fn ($key, $value) => "- {$key} <strong>" . htmlspecialchars($this->formatNewValue($value)) . '</strong>', array_keys($newValues), $newValues);
    }

    private function formatNewValue($value): string
    {
        return is_array($value) ? json_encode($value) : $value ?? '—';
    }
}
