<?php

namespace Rmsramos\Activitylog\Commands;

use Illuminate\Console\Command;

class ActivitylogCommand extends Command
{
    public $signature = 'activitylog';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
