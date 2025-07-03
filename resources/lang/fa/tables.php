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
            'label'                   => 'ورود به سیستم در',
            'created_from'            => 'ایجاد شده از ',
            'created_from_indicator'  => 'ایجاد شده از تاریخ : :created_from',
            'created_until'           => 'ایجاد شده تا ',
            'created_until_indicator' => 'ایجاد شده تا تاریخ : :created_until',
        ],
        'event' => [
            'label' => 'رویداد',
        ],
    ],
];
