<?php

return [
    'columns' => [
        'log_name' => [
            'label' => 'Jenis',
        ],
        'event' => [
            'label' => 'Peristiwa',
        ],
        'subject_type' => [
            'label' => 'Subjek',
        ],
        'causer' => [
            'label' => 'Pengguna',
        ],
        'properties' => [
            'label' => 'Properti',
        ],
        'created_at' => [
            'label' => 'Masuk di',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'         => 'Masuk di',
            'created_from'  => 'Dibuat dari',
            'created_until' => 'Dibuat sampai',
        ],
        'event' => [
            'label' => 'Peristiwa',
        ],
    ],
];
