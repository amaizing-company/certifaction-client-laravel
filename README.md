# Certifaction API Client for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/amaizing-company/certifaction-client-laravel.svg?style=flat-square)](https://packagist.org/packages/amaizing-company/certifaction-client-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/amaizing-company/certifaction-client-laravel/run-tests.yml?branch=1.x&label=tests&style=flat-square)](https://github.com/amaizing-company/certifaction-client-laravel/actions?query=workflow%3Arun-tests+branch%3A1.x)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/amaizing-company/certifaction-client-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/amaizing-company/certifaction-client-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/amaizing-company/certifaction-client-laravel.svg?style=flat-square)](https://packagist.org/packages/amaizing-company/certifaction-client-laravel)

The certifaction client for laravel provides ability to communicate with certifactions signature api. 


## Installation

You can install the package via composer:

```bash
composer require amaizing-company/certifaction-client-laravel
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="certifaction-client-laravel-migrations"
php artisan migrate
```

You should publish the config file with:

```bash
php artisan vendor:publish --tag="certifaction-client-laravel-config"
```

This is the contents of the published config file:

```php
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
];
```

### Certifaction Client

This package needs certifactions api client server to communicate with certifactions cloud services. Please follow the 
[guide from certifactions documentation](https://developers.certifaction.com/guides/installation), to get started with 
the local api client.

## Basic Configuration

To get started you should generate an api key from certifactions web application. Please follow the steps of 
[certifactions local api documentation](https://developers.certifaction.com/guides/getting-started-api). 

You may set the following environment variables to get started. Please replace the default values.

``` dotenv
CERTIFACTION_LOCAL_URI=https://[DOMAIN/IP]:[PORT] # The uri where your local certifaction client is reachable
CERTIFACTION_ADMIN_URI=https://api.certifaction.io # The uri where certifactions admin api is reachable
CERTIFACTION_CLIENT_API_KEY=*********** # Your API Key
```

Now your application should be able to reach the local certifaction client. You could test this by calling the following 
artisan command.

```
php artisan certifaction:server:ping
```

For more detailed information you could also call:

```
php artisan certifaction:server:health
```

When everything is working fine, you should set a default role_id with that new accounts will create. To do that, set 
the `role_id` config value inside the published package configuration file. You can get an overview over the roles inside your certifaction instance with the 
following command:

```
php artisan certifaction:roles:list
```

Thats it. For more detailed configuration options just have a look inside the docs.

## Usage



## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [AmaizingCompany](https://github.com/amaizing-company)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
