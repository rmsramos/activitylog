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
            'label'        => 'Sujet',
            'soft_deleted' => ' (Suppression douce)',
            'deleted'      => ' (Supprimé)',
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
            'label'                   => 'Enregistré à',
            'created_from'            => 'Créé à partir de ',
            'created_from_indicator'  => 'Créé à partir de : :created_from',
            'created_until'           => 'Créé jusqu\'à ',
            'created_until_indicator' => 'Créé jusqu\'à : :created_until',
        ],
        'event' => [
            'label' => 'Événement',
        ],
        'log_name' => [
            'label' => 'Nom du journal',
        ],
    ],
];
