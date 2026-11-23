<?php

namespace Rmsramos\Activitylog\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use Rmsramos\Activitylog\ActivitylogPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->plugins([
                ActivitylogPlugin::make(),
            ]);
    }
}
