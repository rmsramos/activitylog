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
            'label'        => 'Betreff',
            'soft_deleted' => ' (Soft Gelöscht)',
            'deleted'      => ' (Gelöscht)',
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
            'label'                   => 'Logzeitpunkt',
            'created_from'            => 'Geloggt von ',
            'created_from_indicator'  => 'Geloggt von : :created_from',
            'created_until'           => 'Geloggt bis',
            'created_until_indicator' => 'Geloggt bis : :created_until',
        ],
        'event' => [
            'label' => 'Ereignis',
        ],
        'log_name' => [
            'label' => 'Protokollname',
        ],
    ],
];
