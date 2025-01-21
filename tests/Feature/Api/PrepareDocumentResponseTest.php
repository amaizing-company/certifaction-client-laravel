<?php

use AmaizingCompany\CertifactionClient\Api\Responses\PrepareDocumentResponse;
use GuzzleHttp\Psr7\Response;

pest()->group('api');

it('can initiate instance', function (int $status, array $headers) {
    $response = new Response($status, $headers, null);
    $prepareDocumentResponse = new PrepareDocumentResponse($response);

    expect($prepareDocumentResponse)
        ->toBeInstanceOf(PrepareDocumentResponse::class);
})->with([
    [200, ['Location' => 'https://test.example', 'File-Id' => 'test_id']],
    [500, []],
]);

it('can get file location', function (int $status, array $headers) {
    $response = new Response($status, $headers, null);
    $prepareDocumentResponse = new PrepareDocumentResponse($response);

    expect($prepareDocumentResponse)
        ->when($prepareDocumentResponse->successful(), fn ($expectation) => $expectation->getFileLocation()->toBeString()->toBe('https://test.example'))
        ->when($prepareDocumentResponse->successful() === false, fn ($expectation) => $expectation->getFileLocation()->toBeNull());
})->with([
    [200, ['Location' => 'https://test.example', 'File-Id' => 'test_id']],
    [500, []],
]);

it('can get file id', function (int $status, array $headers) {
    $response = new Response($status, $headers, null);
    $prepareDocumentResponse = new PrepareDocumentResponse($response);

    expect($prepareDocumentResponse)
        ->when($prepareDocumentResponse->successful(), fn ($expectation) => $expectation->getFileId()->toBeString()->toBe('test_id'))
        ->when($prepareDocumentResponse->successful() === false, fn ($expectation) => $expectation->getFileId()->toBeNull());
})->with([
    [200, ['Location' => 'https://test.example', 'File-Id' => 'test_id']],
    [500, []],
]);
