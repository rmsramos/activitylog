<?php

return [
    'components' => [
        'created_by_at'             => 'Le <strong>:subject</strong> a été <strong>:event</strong> par <strong>:causer</strong>. <br><small> Mis à jour le : <strong>:update_at</strong></small>',
        'updater_updated'           => ':causer a :event ce qui suit : <br>:changes',
        'from_oldvalue_to_newvalue' => '- :key de <strong>:old_value</strong> à <strong>:new_value</strong>',
        'to_newvalue'               => '- :key <strong>:new_value</strong>',
        'unknown'                   => 'Inconnu',
    ],
];
