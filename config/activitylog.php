<?php

return [
    'resources' => [
        'label' => 'Activity Log',
        'plural_label' => 'Activity Logs',
        'navigation_group' => null,
        'navigation_icon' => 'heroicon-o-shield-check',
        'navigation_sort' => null,
        'navigation_count_badge' => false,
        'resource' => \Rmsramos\Activitylog\Resources\ActivitylogResource::class,
    ],
    'datetime_format' => 'd/m/Y H:i:s',
];
