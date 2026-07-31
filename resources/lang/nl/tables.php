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
            'label'        => 'Onderwerp',
            'soft_deleted' => ' (Soft Verwijderd)',
            'deleted'      => ' (Verwijderd)',
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
            'label'                   => 'Gelogd op',
            'created_from'            => 'Aangemaakt van ',
            'created_from_indicator'  => 'Aangemaakt van : :created_from',
            'created_until'           => 'Aangemaakt tot ',
            'created_until_indicator' => 'Aangemaakt tot : :created_until',
        ],
        'event' => [
            'label' => 'Event',
        ],
        'log_name' => [
            'label' => 'Lognaam',
        ],
    ],
];
