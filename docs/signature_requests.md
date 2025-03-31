# Signature Requests

Signature requests are the core feature of this package. If documents are to be signed by a user, they must first be 
prepared and can then be combined into a signature request. This procedure remains the same regardless of whether one 
document or several documents are to be signed.

## Prepare Documents

First, all documents that are to be signed must be prepared. To do this, the documents are sent to Certifaction. 
Certifaction then sends back a URL under which the documents are made available for signing. These URLs can then be 
used to create a signature request.

To prepare an document, a request can be sent directly via the signable document model of your application. Before you 
start prepare an document you have to create a new signature transaction for the related user to assign the prepared 
documents to it. 

```php
use AmaizingCompany\CertifactionClient\Contracts\CertifactionUser;
use AmaizingCompany\CertifactionClient\Contracts\Signable;
use AmaizingCompany\CertifactionClient\Enums\DocumentPrepareScope;
use AmaizingCompany\CertifactionClient\Enums\SignatureType;

/**
 * @var CertifactionUser $user 
 */
$transaction = $user->createSignatureTransaction(SignatureType::SES);

/**
 * @var Signable $document
 */
$document->requestPreparation(DocumentPrepareScope::SIGN, $transaction);
```
When the request has been processed, either the `DocumentPrepared` or the `DocumentPreparationFailed` event is 
triggered, depending on the status of the request.


## Start a Signature Request

To start a new signature request, you need an already created signature transaction with prepared documents attached. 
Then you can trigger a job by calling the related method from the certifaction client facade.

```php
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;

CertifactionClient::requestSignature($transaction);
```

After the job has finished, it will trigger the `SignatureRequestStarted` or the `SignatureRequestFailed` event. 

A successful signature request will store the url to finish the request by signing the documents inside the signature
transaction record. You can access it like this:

```php
use AmaizingCompany\CertifactionClient\Models\SignatureTransaction;

/**
 * @var SignatureTransaction $transaction
 */
$transaction->request_url;
```

## Finish a Signature Transaction

To finish a signature transaction, the signatory should sign all files under the given request url. If this happened, 
certifaction will call a webhook url. This is the url you have set inside the global package configuration or inside 
your document model. The package already provides an preconfigured webhook workflow, which will call the 
`ProcessWebhook` job. This job handle all documents of the signature transaction by checking their status against the 
status of the signature request, update the database records and download all signed documents inside the 
configured storage.

Depending on the status of the job, it will trigger the `SignatureRequestFinished` or the `SignatureRequestFailed` 
event. 

To ensure a signature transaction will get finished, its recommended to schedule the 
`ProcessPendingSignatureTransactions` job, which will trigger the `ProcessWebhook` job for any pending signature 
transaction. If all documents inside a signature request are unsigned the transaction stays on status pending.

