<?php

use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;
use AmaizingCompany\CertifactionClient\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

if (CertifactionClient::getConfig('default_routes')) {
    Route::get('/signature/finish/{signatureTransaction}', WebhookController::class)
        ->name('certifaction.webhooks.signature.finish');
}

