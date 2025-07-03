<?php

return [
    'columns' => [
        'log_name' => [
            'label' => 'Typ',
        ],
        'event' => [
            'label' => 'Ereignis',
        ],
        'subject_type' => [
            'label' => 'Betreff',
        ],
        'causer' => [
            'label' => 'Benutzer',
        ],
        'properties' => [
            'label' => 'Attribute',
        ],
        'created_at' => [
            'label' => 'Logzeitpunkt',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'         => 'Logzeitpunkt',
            'created_from'  => 'Geloggt von ',
            'created_until' => 'Geloggt bis',
        ],
        'event' => [
            'label' => 'Ereignis',
        ],
    ],
];
