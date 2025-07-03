<?php

namespace Rmsramos\Activitylog\Helpers;

use Illuminate\Support\Str;

class ActivityLogHelper
{
    /**
     * Checks if a class uses a specific trait.
     *
     * @param  mixed  $class  The class or object instance to check.
     * @param  string  $trait  The fully qualified name of the trait to look for.
     */
    public static function classUsesTrait($class, $trait): bool
    {
        $traits = class_uses_recursive($class);

        return in_array($trait, $traits);
    }

    public static function getResourcePluralName($class): string
    {
        return Str::plural(Str::kebab(class_basename($class)));
    }
}
