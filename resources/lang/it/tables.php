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
            'label' => 'Soggetto',
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
            'label'         => 'Loggato il',
            'created_from'  => 'Creato da ',
            'created_until' => 'Creato fino al ',
        ],
        'event' => [
            'label' => 'Evento',
        ],
    ],
];
