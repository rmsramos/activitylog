<?php

namespace Rmsramos\Activitylog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Rmsramos\Activitylog\Activitylog
 */
class Activitylog extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Rmsramos\Activitylog\Activitylog::class;
    }
}
