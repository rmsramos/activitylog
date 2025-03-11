<?php

return [
    'columns' => [
        'log_name' => [
            'label' => 'Tür',
        ],
        'event' => [
            'label' => 'Olay',
        ],
        'subject_type' => [
            'label' => 'Konu',
        ],
        'causer' => [
            'label' => 'Kullanıcı',
        ],
        'properties' => [
            'label' => 'Özellikler',
        ],
        'created_at' => [
            'label' => 'Kaydedilme Tarihi',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'         => 'Kaydedilme Tarihi',
            'created_from'  => 'Başlangıç Tarihi',
            'created_until' => 'Bitiş Tarihi',
        ],
        'event' => [
            'label' => 'Olay',
        ],
    ],
];
