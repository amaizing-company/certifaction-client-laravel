# Models

## Application Models

For the package to work properly, your application needs two types of models. A user model which holds the information 
for the signatory and a document model which holds the basic information about the files.

Your application can also define multiple users and multiple document models. This does not change the workflow.

If the use case is not practical for your application, you can also take a look at the advanced usage documentation 
to define your own workflows.

### User Model

#### Setup

To set up the user model implement the `CertifactionUser` contract and the `HasCertifactionAccount` trait.

```php
use AmaizingCompany\CertifactionClient\Contracts\CertifactionUser;
use AmaizingCompany\CertifactionClient\Concerns\HasCertifactionAccount;

class User extends Model implements CertifactionUser
{
    use HasCertifactionAccount;
    
    // ...
}
```

#### Configuration Methods

To help the package reading the values from your applications user model, its required to define some configuration 
methods inside the model.

##### getBirthdate

Is required and should return the birthdate of the user.

```php
public function getBirthdate(): string|\Illuminate\Support\Carbon
{
    return 'yyyy-mm-dd';
    // or
    return \Illuminate\Support\Carbon::make('2025-01-01');
}
```

##### getEmail

Is required and should return a valid email address.

```php
public function getEmail(): string
{
    return 'test@example.com';
}
```

##### getFirstName

Is required and should return the single first name or multiple first names of the user.

```php
public function getFirstName(): string
{
    return 'John';
    // or
    return "John Edward"
}
```

##### getLastName

Is required and should return the last name of the user.

```php
public function getLastName(): string
{
    return 'Doe';
}
```

##### getMobilePhone

Is required and should return a valid mobile phone number of the user.

```php
public function getMobilePhone(): string
{
    return '+4912345678910';
}
```

### Document Model

#### Setup

To set up the document model implement the `Signable` Contract and the `HasCertifactionDocuments` Concern.

```php
use AmaizingCompany\CertifactionClient\Contracts\Signable;
use AmaizingCompany\CertifactionClient\Concerns\HasCertifactionDocuments;

class Document extends Model implements Signable 
{
    use HasCertifactionDocuments;
    
    // ...
}
```

#### Configuration Methods

To help the package reading some parameters and configurations from your applications documents model, you should define
some configuration methods. Some of them are required. Configuration methods give you the flexibility to configure your 
document types based on the document model.

##### getDocumentName

Is required and should return the name of the document. 

```php
public function getDocumentName(): string
{
    return 'my_document';
}
```

##### getEncryptionKey

Is optional and should return the encryption key with that the document was encrypted if it uses an encryption.
Make sure that the withPassword method returns true if you want use this option.

```php
public function getEncryptionKey(): ?string
{
    return null;
}
```

##### getFileContents

Is required and should return the raw file contents of the document. 

```php
public function getFileContents(): string
{
    return \Illuminate\Support\Facades\Storage::get('PATH_TO_DOCUMENT');
}
```

##### getWebhookUrl

Is required and should return the webhook url that is called when a signatory has finished a signature request. By
default it returns the global configuration for webhook url. 

```php
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;

public function getWebhookUrl(): string
{
    return CertifactionClient::getConfig('webhook_url');
}
```

##### hasAdditionalPage

Is required and should return if the signature should be placed at an additional page. By default, it returns false.

```php
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;

public function hasAdditionalPage(): bool
{
    return false;
}
```

##### hasDigitalTwin

Is required and should return if the document should store as digital twin on certifaction's servers. This will place
a qr code at the document that stores the url to call the digital twin. If this option is set true, the following 
methods need to be set:

- [qrCodePositionX](#qrcodepositionx)
- [qrCodePositionY](#qrcodepositiony)
- [qrCodeHeight](#qrcodeheight)
- [qrCodePageNumber](#qrcodepagenumber)

```php
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;

public function hasDigitalTwin(): bool
{
    return CertifactionClient::getConfig('digital_twin');
}
```

##### isPdfA

Is required and should return if the document is a document in PDF-A standard. By default it returns the global config
for pdf a.

```php
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;

public function isPdfA(): bool
{
    return CertifactionClient::getConfig('pdf_a');
}
```

##### qrCodePositionX

Is required when hasDigitalTwin is set true and should return the x coordinate on where the qr code is placed.

```php
public function qrCodePositionX(): int
{
    return 0;
}
```

##### qrCodePositionY

Is required when hasDigitalTwin is set true and should return the y coordinate on where the qr code is placed.

```php
public function qrCodePositionY(): int
{
    return 0;
}
```

##### qrCodeHeight

Is required when hasDigitalTwin is set true and should return the height in pixel of the qr code.

```php
public function qrCodeHeight(): int
{
    return 50;
}
```

##### qrCodePageNumber

Is required when hasDigitalTwin is set true and should return the page number on where the digital twin should 
be placed.

```php
public function qrCodePageNumber(): int
{
    return 1;
}
```

##### signaturePositionX

Is required and should return the x coordinate on where the signature should be placed.

```php
public function signaturePositionX(): int
{
    return 0;
}
```

##### signaturePositionY

Is required and should return the y coordinate on where the signature should be placed.

```php
public function signaturePositionY(): int
{
    return 0;
}
```

##### signatureHeight

Is required and should return the height of the signature.

```php
public function signatureHeight(): int
{
    return 50;
}
```

##### signaturePageNumber

Is required and should return the page number on where the signature should be placed at.

```php
public function signaturePageNumber(): int
{
    return 1;
}
```

##### storageDisk

Is optional and should return the storage disk on where signed documents will be stored on. If nothing is set, the 
global config for storage_disk is used.

```php
public function storageDisk(): ?string
{
    return null;
}
```

##### storageDirectory

Is optional and should return the storage directory on where signed documents will be stored on. If nothing is set, the
global config for storage_directory is used.

```php
public function storageDirectory(): ?string
{
    return null;
}
```

##### withPassword

Is required and should return if the document is encrypted with an encryption string. If this option is set true, the
[getEncryptionKey](#getencryptionkey) method should return the encryption key for the document.

```php
public function withPassword(): bool
{
    return false;
}
```

## Package Models

Here is a list of all models of this package.

| Model                | Description                                       |
|----------------------|---------------------------------------------------|
| Account              | Holds certifaction account records                |
| Document             | Holds document records for certifaction documents |
| FileTransaction      | Holds records for all file transactions           |
| IdentityTransaction  | Holds records for all identity transactions       |
| SignatureTransaction | Holds records for all signature transactions      |

### Extend Package Models

All package models are fully extendable. The package use a service container binding to make it possible to overwrite 
them from your applications site. 

Here we extend the Account model for example.

```php
use AmaizingCompany\CertifactionClient\Contracts\Account as AccountContract;
use AmaizingCompany\CertifactionClient\Models\Account; 

class MyDefaultAccountModel extends Account implements AccountContract
{
    // ...
}
```

Inside your service provider just bind your default model to the contract.

```php
use AmaizingCompany\CertifactionClient\Contracts\Account;

class AppServiceProvider extends ServiceProvider
{
    public function register() 
    {
        // ...
        
        $this->app->bind(Account::class, MyDefaultAccountModel::class);
    }
}
```

Now the package will use your default model instead of the package model for all defined operations. 
