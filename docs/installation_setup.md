# Installation and setup

## Basic installation

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
    
    /*
     * Enable default package routes.
     */
    'default_routes' => true,
];
```

### Certifaction Local API Server

To setup Certifaction's local api server you need to create a docker container that servs the application. 

Pull the image with:

```bash
docker pull certifaction/server:latest
```

Start the local api with:

```bash
docker run -p 8082:8002 certifaction/server:latest 
```

Certifaction API will be exposed on the the default http://localhost:8082 HTTP server.


### API Key

Receive an api key by following the instructions from 
[Certifactions installation guide](https://developers.certifaction.com/guides/getting-started-api). You will need it for
the next step in configuration.


## Basic Configuration

To make your application able to communicate with Certifaction's services you need to set some environment variables.

Set the local api server uri. This should be the exposed uri from your local certifaction api server.

```dotenv
CERTIFACTION_LOCAL_URI=http://localhost:8082
```

Set the admin api server uri. You can find the correct address by visiting 
[Certifaction's admin api reference](https://developers.certifaction.com/references/admin-api).

```dotenv
CERTIFACTION_ADMIN_URI=https://api.certifaction.io
```

Set the api key:

```dotenv
CERTIFACTION_CLIENT_API_KEY=[YOUR_API_KEY]
```

Now your application should be able to reach Certifaction's api services. To check your local server is reachable, you 
can using the ping command:

```bash
php artisan certifaction:server:ping
```

If you want to have more detailed information about your local server you can use the server health command:

```bash
php artisan certifaction:server:health
```

## Basic Model Configuration

The database models from this package needs two models they defined from application. A user model and a 
document file model. This models needs some preparation before they can used with this package.

### The User Model

The signer model typically are your user models. Each model that should be able to interact with this package needs 
following preparation: 

```php
use AmaizingCompany\CertifactionClient\Contracts\CertifactionUser;
use AmaizingCompany\CertifactionClient\Concerns\HasCertifactionAccount;

class User extends Model implements CertifactionUser
{
    use HasCertifactionAccount;
    
    // ...
}
```

So that the package methods know how to retrieve the required parameters, these must be defined separately via 
methods within the model.

```php
class User extends Model implements CertifactionUser
{
    use HasCertifactionAccount;
    
    public function getEmail(): string 
    {
        // Return the email address for the user.
    }
    
    public function getBirthdate(): string 
    {
        // Return the birthdate for the user. Use format yyyy-mm-dd.
    }
    
    public function getFirstName(): string 
    {
        // Return the first name for the user
    }
    
    public function getLastName(): string 
    {
        // Return the last name for the user
    }
    
    public function getMobilePhone(): string
    {
        // Return the mobile phone number of the user
    }
}
```

### The Document File Model

This models typically handle where your files are stored and how they named. Each model that should be able to interact with this package needs
following preparation:

```php
use AmaizingCompany\CertifactionClient\Concerns\HasCertifactionDocuments;
use AmaizingCompany\CertifactionClient\Contracts\Signable;

class Document extends Model implements Signable
{
    use HasCertifactionDocuments;
    
    // ...
}
```

So that the package methods know how to retrieve the required parameters, these must be defined separately via
methods within the model. 

```php
use AmaizingCompany\CertifactionClient\Contracts\CertifactionUser;

class Document extends Model implements Signable
{
    use HasCertifactionDocuments;
    
    public function getDocumentName(): string
    {
        // Return the name for the document.
    }
    
    public function getFileContents(): string
    {
        // Return the file contnents of the document as raw string
    }
    
    public function getSigner(): CertifactionUser
    {
        // Return a user model that acts as signer for the document type
    }
}
```

## Scheduling

When you are creating signature requests, normally you want to download the signed documents after the request was 
finished on Certifaction's server site. The package creates file transactions for that. To process this file 
transactions it could be useful to schedule the job that done this.

Set the following schedulers to make sure all background tasks will be executed.

```php title="routes/console.php"
// ...
use Illuminate\Support\Facades\Schedule;

Schedule::job(\AmaizingCompany\CertifactionClient\Jobs\ProcessOpenFileTransactions::class)->everyMinute();
Schedule::job(\AmaizingCompany\CertifactionClient\Jobs\ProcessAccountSync::class)->everyFiveMinutes();
Schedule::job(\AmaizingCompany\CertifactionClient\Jobs\ProcessIntentedIdentityTransactions::class)->everyFiveMinutes();
Schedule::job(\AmaizingCompany\CertifactionClient\Jobs\ProcessPendingIdentityTransactions::class)->everyFiveMinutes();
Schedule::job(\AmaizingCompany\CertifactionClient\Jobs\ProcessPendingSignatureTransactions::class)->everyFiveMinutes();
// ...
```

## Broadcasting

All events of this package supporting broadcasting. Broadcasting is disabled by default. It can be enabled by setting
the config for boradcasting to true.

```php title="config/certifaction-client-laravel.php"
// ...
    
broadcasting => true,
    
// ...
```

