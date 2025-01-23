<?php

use AmaizingCompany\CertifactionClient\Api\Responses\AccountIdentificationResponse;
use GuzzleHttp\Psr7\Response;

pest()->group('api', 'responses');

it('can initiate instance', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));

    $accountIdentificationResponse = new AccountIdentificationResponse($response);

    expect($accountIdentificationResponse)
        ->toBeInstanceOf(AccountIdentificationResponse::class);
})->with([
    [200, [], ['id' => 'test', 'identification_uri' => 'https://test.example']],
    [400, [], []],
]);

it('can get identity id', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));

    $accountIdentificationResponse = new AccountIdentificationResponse($response);

    expect($accountIdentificationResponse)
        ->when($accountIdentificationResponse->successful(), fn ($accountIdentificationResponse) => $accountIdentificationResponse->getIdentityId()->toBeString())
        ->when($accountIdentificationResponse->successful() === false, fn ($accountIdentificationResponse) => $accountIdentificationResponse->getIdentityId()->toBeNull());

})->with([
    [200, [], ['id' => 'test', 'identification_uri' => 'https://test.example']],
    [400, [], []],
]);

it('can get identification url', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));

    $accountIdentificationResponse = new AccountIdentificationResponse($response);

    expect($accountIdentificationResponse)
        ->when($accountIdentificationResponse->successful(), fn ($accountIdentificationResponse) => $accountIdentificationResponse->getIdentificationUri()->toBeString())
        ->when($accountIdentificationResponse->successful() === false, fn ($accountIdentificationResponse) => $accountIdentificationResponse->getIdentificationUri()->toBeNull());

})->with([
    [200, [], ['id' => 'test', 'identification_uri' => 'https://test.example']],
    [400, [], []],
]);
