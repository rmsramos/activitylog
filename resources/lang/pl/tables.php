<?php

return [
    'columns' => [
        'log_name' => [
            'label' => 'Typ',
        ],
        'event' => [
            'label' => 'Zdarzenie',
        ],
        'subject_type' => [
            'label' => 'Element',
        ],
        'causer' => [
            'label' => 'Użytkownik',
        ],
        'properties' => [
            'label' => 'Właściwości',
        ],
        'created_at' => [
            'label' => 'Data zdarzenia',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'         => 'Data zdarzenia',
            'created_from'  => 'Utworzony od ',
            'created_until' => 'Utworzony do ',
        ],
        'event' => [
            'label' => 'Zdarzenie',
        ],
    ],
];
