<?php

return [
    'components' => [
        'created_by_at'             => '<strong>:subject</strong> wurde von <strong>:causer</strong> <strong>:event</strong>. <br><small> Aktualisiert am: <strong>:update_at</strong></small>',
        'updater_updated'           => ':causer hat :event folgendes: <br>:changes',
        'from_oldvalue_to_newvalue' => '- :key von <strong>:old_value</strong> auf <strong>:new_value</strong>',
        'to_newvalue'               => '- :key <strong>:new_value</strong>',
        'unknown'                   => 'Unbekannt',
    ],
];
