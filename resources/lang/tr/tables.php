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
            'label'        => 'Konu',
            'soft_deleted' => ' (Yumuşak Silindi)',
            'deleted'      => ' (Silindi)',
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
            'label'                   => 'Kayıt Tarihi',
            'created_from'            => 'Kayıt tarihinden ',
            'created_from_indicator'  => 'Kayıt tarihinden : :created_from',
            'created_until'           => 'Kayıt tarihine ',
            'created_until_indicator' => 'Kayıt tarihine : :created_until',
        ],
        'event' => [
            'label' => 'Olay',
        ],
        'log_name' => [
            'label' => 'Günlük Adı',
        ],
    ],
];
