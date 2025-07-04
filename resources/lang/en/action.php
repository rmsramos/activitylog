<?php

return [
    'modal' => [
        'heading'     => 'User Activity Log',
        'description' => 'Track all user activities',
        'tooltip'     => 'User Activities',
    ],
    'event' => [
        'created'  => 'created',
        'deleted'  => 'deleted',
        'updated'  => 'updated',
        'restored' => 'restored',
    ],
    'view'                => 'View',
    'edit'                => 'Edit',
    'restore'             => 'Restore',
    'restore_soft_delete' => [
        'label'             => 'Restore Model',
        'modal_heading'     => 'Restore Deleted Model',
        'modal_description' => 'This will restore the model that was deleted (soft delete).',
    ],
];
