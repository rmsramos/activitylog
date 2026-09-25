<?php

namespace Rmsramos\Activitylog\Helpers;

use Closure;
use Illuminate\Database\Eloquent\Model;

/**
 * Reads and writes activity payloads across spatie/laravel-activitylog v4 and v5.
 *
 * v4 stores attribute diffs in properties.old / properties.attributes.
 * v5 stores them in attribute_changes and keeps properties for custom data only.
 * v5 also renamed withoutLogs() to withoutLogging() and added withChanges().
 */
class ActivityChanges
{
    /**
     * @return array{old: array<string, mixed>|null, attributes: array<string, mixed>|null}
     */
    public static function changes(object $activity): array
    {
        $stored = self::toArray($activity->attribute_changes ?? null);

        if (! array_key_exists('old', $stored) && ! array_key_exists('attributes', $stored)) {
            $stored = self::toArray($activity->properties ?? null);
        }

        return [
            'old'        => is_array($stored['old'] ?? null) ? $stored['old'] : null,
            'attributes' => is_array($stored['attributes'] ?? null) ? $stored['attributes'] : null,
        ];
    }

    /**
     * Custom properties, without the v4 attribute diff keys.
     *
     * @return array<string, mixed>
     */
    public static function customProperties(object $activity): array
    {
        $properties = self::toArray($activity->properties ?? null);

        unset($properties['old'], $properties['attributes']);

        return $properties;
    }

    /**
     * Payload the timeline and properties column already understand:
     * custom properties plus the attribute diff, from whichever column holds it.
     *
     * @return array<string, mixed>
     */
    public static function timelineProperties(object $activity): array
    {
        $properties = self::customProperties($activity);
        $changes    = self::changes($activity);

        if ($changes['old'] !== null) {
            $properties['old'] = $changes['old'];
        }

        if ($changes['attributes'] !== null) {
            $properties['attributes'] = $changes['attributes'];
        }

        return $properties;
    }

    public static function hasVisibleDetails(object $activity): bool
    {
        $changes = self::changes($activity);

        return self::customProperties($activity) !== []
            || $changes['old'] !== null
            || $changes['attributes'] !== null;
    }

    public static function withoutLogging(Closure $callback): mixed
    {
        $logger = activity();

        if (method_exists($logger, 'withoutLogging')) {
            return $logger->withoutLogging($callback);
        }

        return $logger->withoutLogs($callback);
    }

    /**
     * @param  array{old?: mixed, attributes?: mixed}  $changes
     * @param  array<string, mixed>  $properties
     */
    public static function logRestored(Model $subject, Model $causer, array $changes, array $properties = []): void
    {
        $logger = activity()
            ->performedOn($subject)
            ->causedBy($causer);

        if (method_exists($logger, 'withChanges')) {
            $logger->withChanges($changes);

            if ($properties !== []) {
                $logger->withProperties($properties);
            }
        } else {
            $logger->withProperties([...$changes, ...$properties]);
        }

        $logger
            ->tap(function ($log) {
                $log->event = 'restored';
            })
            ->log('restored');
    }

    /**
     * @return array<string, mixed>
     */
    protected static function toArray(mixed $value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        if (is_array($value)) {
            return $value;
        }

        if (is_object($value) && method_exists($value, 'toArray')) {
            $array = $value->toArray();

            return is_array($array) ? $array : [];
        }

        return [];
    }
}
