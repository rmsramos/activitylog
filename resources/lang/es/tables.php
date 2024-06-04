<?php

return [
    'columns' => [
        'log_name' => [
            'label' => 'Tipo',
        ],
        'event' => [
            'label' => 'Evento',
        ],
        'subject_type' => [
            'label' => 'Asunto',
        ],
        'causer' => [
            'label' => 'Usuario',
        ],
        'properties' => [
            'label' => 'Propiedades',
        ],
        'created_at' => [
            'label' => 'Registrado en',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'         => 'Registrado en',
            'created_from'  => 'Creado desde ',
            'created_until' => 'Creado hasta ',
        ],
        'event' => [
            'label' => 'Evento',
        ],
    ],
];
