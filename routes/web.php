<?php

use AmaizingCompany\CertifactionClient\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get(config('/signature/finish/{signatureTransaction}'), WebhookController::class)
    ->name('certifaction.webhooks.signature.finish');
