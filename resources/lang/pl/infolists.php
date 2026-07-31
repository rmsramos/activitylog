<?php

return [
    'components' => [
        'created_by_at'             => '<strong>:subject</strong> zostało <strong>:event</strong> przez <strong>:causer</strong>. <br><small> Zaktualizowano: <strong>:update_at</strong></small>',
        'updater_updated'           => ':causer :event następujące: <br>:changes',
        'from_oldvalue_to_newvalue' => '- :key z <strong>:old_value</strong> na <strong>:new_value</strong>',
        'to_newvalue'               => '- :key <strong>:new_value</strong>',
        'unknown'                   => 'Nieznany',
    ],
];
