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
            'label'        => 'Subject',
            'soft_deleted' => ' (Soft Deleted)',
            'deleted'      => ' (Deleted)',
        ],
        'causer' => [
            'label' => 'User',
        ],
        'properties' => [
            'label' => 'Properties',
        ],
        'created_at' => [
            'label' => 'Logged at',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'                   => 'Logged at',
            'created_from'            => 'Created from ',
            'created_from_indicator'  => 'Created from  : :created_from',
            'created_until'           => 'Created until ',
            'created_until_indicator' => 'Created until  : :created_until',
        ],
        'event' => [
            'label' => 'Event',
        ],
    ],
];
