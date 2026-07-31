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
            'label'        => 'נושא',
            'soft_deleted' => ' (נמחק רכות)',
            'deleted'      => ' (נמחק)',
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
            'label'                   => 'נוצר',
            'created_from'            => 'נוצר מ',
            'created_from_indicator'  => 'נוצר מתאריך : :created_from',
            'created_until'           => 'נוצר עד',
            'created_until_indicator' => 'נוצר עד תאריך : :created_until',
        ],
        'event' => [
            'label' => 'אירוע',
        ],
        'log_name' => [
            'label' => 'שם היומן',
        ],
    ],
];
