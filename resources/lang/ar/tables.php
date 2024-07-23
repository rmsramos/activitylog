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
            'label' => 'الموضوع',
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
            'label'         => 'تاريخ التسجيل',
            'created_from'  => 'تاريخ الإنشاء من',
            'created_until' => 'تاريخ الإنشاء حتى',
        ],
        'event' => [
            'label' => 'الحدث',
        ],
    ],
];
