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
            'label' => 'Assunto',
        ],
        'causer' => [
            'label' => 'Usuário',
        ],
        'properties' => [
            'label' => 'Propriedades',
        ],
        'created_at' => [
            'label' => 'Criado em',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'         => 'Criado em',
            'created_from'  => 'Criado a partir de ',
            'created_until' => 'Criado até ',
        ],
        'event' => [
            'label' => 'Eventos',
        ],
    ],
];
