<?php

return [
    'columns' => [
        'log_name' => [
            'label' => 'Tip',
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
            'label' => 'Kayıt Tarihi',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'         => 'Kayıt Tarihi',
            'created_from'  => 'Kayıt tarihinden ',
            'created_until' => 'Kayıt tarihine ',
        ],
        'event' => [
            'label' => 'Olay',
        ],
    ],
];
