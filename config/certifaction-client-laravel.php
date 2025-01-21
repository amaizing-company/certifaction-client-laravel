<?php

use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;

return [
    'api' => [
        'environments' => [
            CertifactionEnvironment::LOCAL->value => env('CERTIFACTION_LOCAL_URI'),
            CertifactionEnvironment::ADMIN->value => env('CERTIFACTION_ADMIN_URI'),
        ],
        'auth' => [
            'key' => env('CERTIFACTION_CLIENT_API_KEY'),
        ]
    ],

    'database' => [
        'table_prefix' => 'certifaction_',
    ],

    // Webhook Url which is called when a signature request has been completed by the signatory.
    'webhook_url' => '',
];
