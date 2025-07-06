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
            'label'        => 'Sujeto',
            'soft_deleted' => ' (borrado lógico)',
            'deleted'      => ' (eliminado)',
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
            'label'                   => 'Registrado en',
            'created_from'            => 'Creado desde ',
            'created_from_indicator'  => 'Creado desde: :created_from',
            'created_until'           => 'Creado hasta ',
            'created_until_indicator' => 'Creado hasta: :created_until',
        ],
        'event' => [
            'label' => 'Evento',
        ],
    ],
];
