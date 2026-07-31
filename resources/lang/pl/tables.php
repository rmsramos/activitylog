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
            'label'        => 'Element',
            'soft_deleted' => ' (usunięto miękko)',
            'deleted'      => ' (usunięto)',
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
            'label'                   => 'Data zdarzenia',
            'created_from'            => 'Utworzony od ',
            'created_from_indicator'  => 'Utworzony od : :created_from',
            'created_until'           => 'Utworzony do ',
            'created_until_indicator' => 'Utworzony do : :created_until',
        ],
        'event' => [
            'label' => 'Zdarzenie',
        ],
        'log_name' => [
            'label' => 'Nazwa dziennika',
        ],
    ],
];
