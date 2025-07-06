<?php

return [
    'modal' => [
        'heading'     => 'Registro de actividad de usuario',
        'description' => 'Realizar un seguimiento de todas las actividades de los usuarios',
        'tooltip'     => 'Actividades de usuario',
    ],
    'event' => [
        'created'  => 'creado',
        'deleted'  => 'eliminado',
        'updated'  => 'actualizado',
        'restored' => 'restaurado',
    ],
    'view'                => 'Ver',
    'edit'                => 'Editar',
    'restore'             => 'Restaurar',
    'restore_soft_delete' => [
        'label'             => 'Restaurar modelo',
        'modal_heading'     => 'Restaurar modelo eliminado',
        'modal_description' => 'Esto restaurará el modelo que fue eliminado (borrado lógico).',
    ],
];
