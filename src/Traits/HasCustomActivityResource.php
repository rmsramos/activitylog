<?php

namespace Rmsramos\Activitylog\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasCustomActivityResource
{
    /**
     * Retrieve the associated Filament resource model.
     *
     * @param  Model  $record  The activity log record providing context.
     */
    public function getFilamentActualResourceModel($record): Model
    {
        return $record;
    }
}
