<?php

return [
    'columns' => [
        'log_name' => [
            'label' => 'Tips',
        ],
        'event' => [
            'label' => 'Notikums',
        ],
        'subject_type' => [
            'label'        => 'Objekts',
            'soft_deleted' => ' (mīksti dzēsts)',
            'deleted'      => ' (dzēsts)',
        ],
        'causer' => [
            'label' => 'Lietotājs',
        ],
        'properties' => [
            'label' => 'Īpašības',
        ],
        'created_at' => [
            'label' => 'Ierakstīts',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'                   => 'Ierakstīts',
            'created_from'            => 'Izveidots no ',
            'created_from_indicator'  => 'Izveidots no  : :created_from',
            'created_until'           => 'Izveidots līdz ',
            'created_until_indicator' => 'Izveidots līdz  : :created_until',
        ],
        'event' => [
            'label' => 'Notikums',
        ],
        'log_name' => [
            'label' => 'Ieraksta veids',
        ],
    ],
];
