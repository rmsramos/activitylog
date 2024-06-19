<?php
return [
    'columns' => [
        'log_name' => [
            'label' => 'Type',
        ],
        'event' => [
            'label' => 'Événement',
        ],
        'subject_type' => [
            'label' => 'Sujet',
        ],
        'causer' => [
            'label' => 'Utilisateur',
        ],
        'properties' => [
            'label' => 'Propriétés',
        ],
        'created_at' => [
            'label' => 'Enregistré à',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'         => 'Enregistré à',
            'created_from'  => 'Créé à partir de ',
            'created_until' => 'Créé jusqu\'à ',
        ],
        'event' => [
            'label' => 'Événement',
        ],
    ],
];
