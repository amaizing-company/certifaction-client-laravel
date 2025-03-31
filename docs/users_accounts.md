# Users & Accounts

To interact with this package, the user models of your application needs to be setup correctly. Please have a look into 
[Installation & Setup](/installation_setup#the-user-model) to prepare your user models.

Accounts are necessary to create signature requests from this package. Before you starting have a look into other types
of usage, make sure you have read this topic.

## Invite a user to Certifaction

Before getting start to invite a user to certifaction make sure you know the existing role ID's and have configured a 
default role ID inside the package configuration. To get an overview to all existing role ID's of your ceriifaction 
instance, you can call the following command:

```bash
php artisan certifaction:roles:list
```

From the output you can choose a role ID and copy it to the configuration key. We recommend don't choosing the admin 
role ID to avoid potential security issues.

```php title="config/certifaction-client-laravel.php"
return [
    // ...
    
    role_id => YOUR_DEFAULT_ROLE_ID,
    
    // ...
]
```

Now the package is able to get a default role id for user invitations.

To invite a existing user to certifaction you can call a predefined job like this: 

```php
use App\Models\User;

/**
 * @var User $user
 */
$user->inviteToCertifaction();
```

If you want to specify a different role ID for an invitation you can do this by setting the second parameter with the 
role ID.

```php
$user->inviteToCertifaction('ROLE_ID');
```

You also can call the job directly;
```php
use AmaizingCompany\CertifactionClient\Jobs\ProcessUserInvitation;

ProcessUserInvitation::dispatch($user, 'ROLE_ID');
```

If the job was successful the job will trigger the `UserInvitedToCertifaction` event and a new account relationship will 
create for the given user. You can access the account record by calling:

```php
$user->certifactionAccount()->first();
```

Certifaction won't call a webhook event if an invited user has accepted the invitation. So the status of the
certifaction account inside you application will stay on `INVITED` even the user already joined. To solve this problem
this package can check against the existing accounts of your certifaction instance and synchronize the status of 
the existing accounts. If you want that your application done this job on background, make sure you have set up a 
schedule for it. 

```php title="routes/console.php"
// ...

Schedule::job(\AmaizingCompany\CertifactionClient\Jobs\ProcessAccountSync::class)->everyFiveMinutes();

// ...
```

## Request an Identification

If you want that your users able to sign documents they require a higher signature level like QES, they have to prove 
their identity to Certifaction. You can request an account identification directly from the Account model.

```php
use AmaizingCompany\CertifactionClient\Contracts\Account;
use AmaizingCompany\CertifactionClient\Enums\DocumentType;

/**
 * @var Account $account 
 */
$account->requestIdentification(DocumentType::ID_CARD);
```

You see a document type was used to create the identification request. Certifaction accepts two types of documents for 
identification. You can call them with the `AmaizingCompany\CertifactionClient\Enums\DocumentType` Enum.

- DocumentType::ID_CARD
- DocumentType::PASSPORT

The identification request also includes an jurisdiction from `AmaizingCompany\CertifactionClient\Enums\Jurisdiction`. 
By default, the jurisdiction is set to `Jurisdiction::eIDAS` that is used in the European Union. If the users should 
be identified for the Swiss, you should change the default jurisdiction inside the config to `Jurisdiction::ZertES`.

If an identity request was started, a new identity transaction will be created. You can get the current identity 
transaction record from the Account model. From there you can get the url where the user can identify himself.

```php
$transaction = $account->getPendingIdentityTransaction();

$transaction->identification_url;
```

Certifaction won't call a webhook event if an identification was finished. So the status of the identification 
transaction stays on pending even if the user has identified himself. To solve this problem this package can check 
against the existing accounts of your certifaction instance and synchronize the status of the existing accounts. 
If you want that your application done this job on background, make sure you have set up a schedule for it. 

```php title="routes/console.php"
// ...

Schedule::job(\AmaizingCompany\CertifactionClient\Jobs\ProcessPendingIdentityTransactions::class)->everyFiveMinutes();

// ...
```

The status of an identification request can also be queried directly via the connected model.

```php
$account->requestIdentificationStatusCheck();
```

## Request a deletion

To delete an account, a request can be started directly from the account model. If the request is successful, the 
corresponding record is also deleted in the background.

```php
use AmaizingCompany\CertifactionClient\Contracts\Account;

/**
 * @var Account $account
 */
$account->requestDeletion();
```
