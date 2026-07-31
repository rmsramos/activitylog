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
            'label'        => 'Subjek',
            'soft_deleted' => ' (Dihapus Sementara)',
            'deleted'      => ' (Dihapus)',
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
            'label'                   => 'Masuk di',
            'created_from'            => 'Dibuat dari',
            'created_from_indicator'  => 'Dibuat dari : :created_from',
            'created_until'           => 'Dibuat sampai',
            'created_until_indicator' => 'Dibuat sampai : :created_until',
        ],
        'event' => [
            'label' => 'Peristiwa',
        ],
        'log_name' => [
            'label' => 'Nama Log',
        ],
    ],
];
