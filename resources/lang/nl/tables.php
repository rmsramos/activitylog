<?php

return [
    'columns' => [
        'log_name' => [
            'label' => 'Type',
        ],
        'event' => [
            'label' => 'Event',
        ],
        'subject_type' => [
            'label' => 'Onderwerp',
        ],
        'causer' => [
            'label' => 'Gebruiker',
        ],
        'properties' => [
            'label' => 'Velden',
        ],
        'created_at' => [
            'label' => 'Gelogd op',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'         => 'Gelogd op',
            'created_from'  => 'Aangemaakt van ',
            'created_until' => 'Aangemaakt tot ',
        ],
        'event' => [
            'label' => 'Event',
        ],
    ],
];
