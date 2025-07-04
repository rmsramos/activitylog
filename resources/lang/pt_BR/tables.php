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
            'label'        => 'Assunto',
            'soft_deleted' => ' (Delete suave)',
            'deleted'      => ' (Deletado)',
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
            'label'                   => 'Criado em',
            'created_from'            => 'Criado a partir de ',
            'created_from_indicator'  => 'Criado a partir de  : :created_from',
            'created_until'           => 'Criado até ',
            'created_until_indicator' => 'Criado até  : :created_until',
        ],
        'event' => [
            'label' => 'Eventos',
        ],
    ],
];
