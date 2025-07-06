<?php

return [
    'components' => [
        'created_by_at'             => 'El <strong>:subject</strong> fue <strong>:event</strong> por <strong>:causer</strong>. <br><small> Actualizado en: <strong>:update_at</strong></small>',
        'updater_updated'           => ':causer :event lo siguiente: <br>:changes',
        'from_oldvalue_to_newvalue' => '- :key de <strong>:old_value</strong> a <strong>:new_value</strong>',
        'to_newvalue'               => '- :key <strong>:new_value</strong>',
        'unknown'                   => 'Desconocido',
    ],
];
