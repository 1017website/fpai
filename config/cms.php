<?php

return [
    'superadmin' => [
        'name' => env('CMS_SUPERADMIN_NAME', 'Superadmin FPAI'),
        'email' => env('CMS_SUPERADMIN_EMAIL', 'superadmin@fpai.or.id'),
        'password' => env('CMS_SUPERADMIN_PASSWORD', 'FpaiSuperadmin!2026'),
    ],
    'developer' => [
        'name' => env('CMS_DEVELOPER_NAME', 'Developer FPAI'),
        'email' => env('CMS_DEVELOPER_EMAIL', 'developer@fpai.or.id'),
        'password' => env('CMS_DEVELOPER_PASSWORD', 'FpaiDeveloper!2026'),
    ],
];
