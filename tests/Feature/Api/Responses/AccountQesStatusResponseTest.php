<?php

use AmaizingCompany\CertifactionClient\Api\DataObjects\QesStatusItem;
use AmaizingCompany\CertifactionClient\Api\Responses\AccountQesStatusResponse;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;

pest()->group('api', 'responses');

it('can initiate instance', function () {
    $response = new Response(200, [], json_encode($this->getAccountQesStatusResponseData()));
    $accountQesStatusResponse = new AccountQesStatusResponse($response);

    expect($accountQesStatusResponse)
        ->toBeInstanceOf(AccountQesStatusResponse::class);
});

it('can initiate instance on bad response', function () {
    $response = new Response(500, [], null);
    $accountQesStatusResponse = new AccountQesStatusResponse($response);

    expect($accountQesStatusResponse)
        ->toBeInstanceOf(AccountQesStatusResponse::class);
});

it('can check if has qes status items when items provided', function () {
    $response = new Response(200, [], json_encode($this->getAccountQesStatusResponseData()));
    $accountQesStatusResponse = new AccountQesStatusResponse($response);

    expect($accountQesStatusResponse->hasQesStatusItems())
        ->toBeBool()
        ->toBeTrue();
});

it('can check if has qes status items when no items provided', function () {
    $response = new Response(200, [], null);
    $accountQesStatusResponse = new AccountQesStatusResponse($response);

    expect($accountQesStatusResponse->hasQesStatusItems())
        ->toBeBool()
        ->toBeFalse();
});

it('can get qes status items when items provided', function () {
    $response = new Response(200, [], json_encode($this->getAccountQesStatusResponseData()));
    $accountQesStatusResponse = new AccountQesStatusResponse($response);

    expect($accountQesStatusResponse->getQesStatusItems())
        ->toBeInstanceOf(Collection::class)
        ->not->toBeEmpty()
        ->toHaveCount(2);
});

it('can get qes status items when no items provided', function () {
    $response = new Response(200, [], null);
    $accountQesStatusResponse = new AccountQesStatusResponse($response);

    expect($accountQesStatusResponse->getQesStatusItems())
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});

it ('can get qes status item by jurisdiction', function () {
    $response = new Response(200, [], json_encode($this->getAccountQesStatusResponseData()));
    $accountQesStatusResponse = new AccountQesStatusResponse($response);

    expect($accountQesStatusResponse->getQesStatusItemByJurisdiction('eIDAS'))
        ->toBeInstanceOf(QesStatusItem::class)
        ->and($accountQesStatusResponse->getQesStatusItemByJurisdiction('bad_input'))
        ->toBeNull();
});
