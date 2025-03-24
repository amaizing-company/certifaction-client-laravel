<?php

namespace AmaizingCompany\CertifactionClient\Http\Controllers;

use AmaizingCompany\CertifactionClient\Contracts\SignatureTransaction;
use AmaizingCompany\CertifactionClient\Jobs\ProcessWebhook;

class WebhookController
{
    public function __invoke(SignatureTransaction $transaction)
    {
        ProcessWebhook::dispatch($transaction);

        return response()->json(['webhook_successful' => true, 'message' => 'Webhook handled successfully']);
    }
}
