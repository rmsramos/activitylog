<?php

return [
    'columns' => [
        'log_name' => [
            'label' => 'סוג',
        ],
        'event' => [
            'label' => 'אירוע',
        ],
        'subject_type' => [
            'label' => 'נושא',
        ],
        'causer' => [
            'label' => 'משתמש',
        ],
        'properties' => [
            'label' => 'מאפיינים',
        ],
        'created_at' => [
            'label' => 'נוצר',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'         => 'נוצר',
            'created_from'  => 'נוצר מ',
            'created_until' => 'נוצר עד',
        ],
        'event' => [
            'label' => 'אירוע',
        ],
    ],
];
