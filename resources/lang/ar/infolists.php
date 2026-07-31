<?php

return [
    'components' => [
        'created_by_at'             => '<strong>:subject</strong> تم <strong>:event</strong> بواسطة <strong>:causer</strong>. <br><small> تم التحديث في: <strong>:update_at</strong></small>',
        'updater_updated'           => ':causer قام بـ :event التالي: <br>:changes',
        'from_oldvalue_to_newvalue' => '- :key من <strong>:old_value</strong> إلى <strong>:new_value</strong>',
        'to_newvalue'               => '- :key <strong>:new_value</strong>',
        'unknown'                   => 'غير معروف',
    ],
];
