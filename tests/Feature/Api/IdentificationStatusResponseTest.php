<?php

use AmaizingCompany\CertifactionClient\Api\Responses\IdentificationStatusResponse;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use GuzzleHttp\Psr7\Response;

pest()->group('api');

it('can initiate instance', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));
    $identificationStatusResponse = new IdentificationStatusResponse($response);

    expect($identificationStatusResponse)
        ->toBeInstanceOf(IdentificationStatusResponse::class);
})->with([
    [200, [], [
        'id' => 'test_id',
        'status' => 'pending',
        'identification_method' => 'autoident',
    ]],
    [500, [], []],
]);

it('can get identification id', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));
    $identificationStatusResponse = new IdentificationStatusResponse($response);

    expect($identificationStatusResponse)
        ->when($identificationStatusResponse->successful(), fn ($expectation) => $expectation->getIdentificationId()->toBeString()->toBe('test_id'))
        ->when($identificationStatusResponse->successful() === false, fn ($expectation) => $expectation->getIdentificationId()->toBeNull());
})->with([
    [200, [], [
        'id' => 'test_id',
        'status' => 'pending',
        'identification_method' => 'autoident',
    ]],
    [500, [], []],
]);

it('can get identification status', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));
    $identificationStatusResponse = new IdentificationStatusResponse($response);

    expect($identificationStatusResponse)
        ->when($identificationStatusResponse->successful(), fn ($expectation) => $expectation->getIdentificationStatus()->toBeInstanceOf(IdentificationStatus::class)->toBe(IdentificationStatus::PENDING))
        ->when($identificationStatusResponse->successful() === false, fn ($expectation) => $expectation->getIdentificationStatus()->toBeNull());
})->with([
    [200, [], [
        'id' => 'test_id',
        'status' => 'pending',
        'identification_method' => 'autoident',
    ]],
    [500, [], []],
]);
