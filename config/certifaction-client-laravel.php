<?php

use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;

return [
    /*
     * API
     *
     * Set environment endpoints and credentials to initiate communication with certifaction api servers.
     * To avoid security risks, this configuration should only be defined via the environment variables.
     */
    'api' => [
        'environments' => [
            CertifactionEnvironment::LOCAL->value => env('CERTIFACTION_LOCAL_URI'),
            CertifactionEnvironment::ADMIN->value => env('CERTIFACTION_ADMIN_URI'),
        ],
        'auth' => [
            'key' => env('CERTIFACTION_CLIENT_API_KEY'),
        ],
    ],

    /*
     * DATABASE
     *
     * Defines the behavior of database migrations.
     */
    'database' => [
        'table_prefix' => 'certifaction_',
    ],

    /*
     * The default jurisdiction that used to create requests.
     */
    'jurisdiction' => Jurisdiction::EIDAS,

    /*
     * The default role ID for new users.
     */
    'role_id' => '',

    /*
     * Determines if certifaction should store a digital twin of documents in there cloud. This can be overwritten from config
     * of a signable document and is used when no other config provided.
     */
    'digital_twin' => false,

    /*
     * Determines if documents are in general in pdf-a format. This can be overwritten from config
     * of a signable document and is used when no other config provided.
     */
    'pdf_a' => false,

    /*
     * Determines if certifaction should notify the signer about a new request. This can be overwritten from config
     * of a signable document and is used when no other config provided.
     */
    'notify_signer' => false,

    /*
     * The default webhook url that is called when a signature request finished. This can be overwritten from config
     * of a signable document and is used when no other config provided.
     */
    'webhook_url' => '',

    /*
     * STORAGE
     *
     * The storage config allows to define where signed and downloaded documents should be stored. This config can be
     * overwritten inside the model configuration of a signable document model that uses the certifaction documents
     * concern.
     */
    'storage_disk' => 'local',
    'storage_directory' => 'certifaction/documents',

    /*
     * BROADCASTING
     *
     * If broadcasting is expected for events set this option true.
     */
    'broadcasting' => false,

    /*
     * Enable default package routes.
     */
    'default_routes' => true,
];
