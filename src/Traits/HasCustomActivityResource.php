<?php

namespace Rmsramos\Activitylog\Traits;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Str;

trait HasCustomActivityResource
{
    /**
     * Retrieve the associated Filament resource model.
     *
     * @param  Model  $record  The activity log record providing context.
     * @return Model
     */
    public function getFilamentActualResourceModel($record) : Model
    {
        return $record;
    }
}
