<?php

return [
    'columns' => [
        'log_name' => [
            'label' => 'نوع',
        ],
        'event' => [
            'label' => 'رویداد',
        ],
        'subject_type' => [
            'label' => 'مفعول',
        ],
        'causer' => [
            'label' => 'کاربر',
        ],
        'properties' => [
            'label' => 'تغییرات',
        ],
        'created_at' => [
            'label' => 'ورود به سیستم در',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'         => 'ورود به سیستم در',
            'created_from'  => 'ایجاد شده از',
            'created_until' => 'ایجاد شده تا',
        ],
        'event' => [
            'label' => 'رویداد',
        ],
    ],
];
