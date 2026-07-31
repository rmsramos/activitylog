<?php

return [
    'columns' => [
        'log_name' => [
            'label' => 'Tipo',
        ],
        'event' => [
            'label' => 'Evento',
        ],
        'subject_type' => [
            'label'        => 'Soggetto',
            'soft_deleted' => ' (Eliminazione soft)',
            'deleted'      => ' (Eliminato)',
        ],
        'causer' => [
            'label' => 'Utente',
        ],
        'properties' => [
            'label' => 'Proprietà',
        ],
        'created_at' => [
            'label' => 'Loggato il',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'                   => 'Loggato il',
            'created_from'            => 'Creato da ',
            'created_from_indicator'  => 'Creato da : :created_from',
            'created_until'           => 'Creato fino al ',
            'created_until_indicator' => 'Creato fino al : :created_until',
        ],
        'event' => [
            'label' => 'Evento',
        ],
        'log_name' => [
            'label' => 'Nome log',
        ],
    ],
];
