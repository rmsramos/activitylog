<?php

return [
    'columns' => [
        'log_name' => [
            'label' => 'النوع',
        ],
        'event' => [
            'label' => 'الحدث',
        ],
        'subject_type' => [
            'label'        => 'الموضوع',
            'soft_deleted' => ' (محذوف مؤقتًا)',
            'deleted'      => ' (محذوف)',
        ],
        'causer' => [
            'label' => 'المستخدم',
        ],
        'properties' => [
            'label' => 'الخصائص',
        ],
        'created_at' => [
            'label' => 'تاريخ التسجيل',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'                   => 'تاريخ التسجيل',
            'created_from'            => 'تاريخ الإنشاء من',
            'created_from_indicator'  => 'تم الإنشاء من : :created_from',
            'created_until'           => 'تاريخ الإنشاء حتى',
            'created_until_indicator' => 'تم الإنشاء حتى : :created_until',
        ],
        'event' => [
            'label' => 'الحدث',
        ],
        'log_name' => [
            'label' => 'نوع السجل',
        ],
    ],
];
