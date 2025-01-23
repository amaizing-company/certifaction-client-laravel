<?php

use AmaizingCompany\CertifactionClient\Api\Responses\PdfFileResponse;
use GuzzleHttp\Psr7\Response;

pest()->group('api', 'responses');

it('can initiate instance', function (int $status, array $headers) {
    $response = new Response($status, $headers, $this->getPdfFileContents());
    $pdfFileResponse = new PdfFileResponse($response);

    expect($pdfFileResponse)
        ->toBeInstanceOf(PdfFileResponse::class);

})->with([
    [200, ['Content-Type' => 'application/pdf']],
    [500, []],
]);

it('can get file contents', function () {
    $response = new Response(200, ['Content-Type' => 'application/pdf'], $this->getPdfFileContents());
    $pdfFileResponse = new PdfFileResponse($response);

    expect($pdfFileResponse->getFileContents())
        ->toBeString()
        ->toBe($this->getPdfFileContents())
        ->and($pdfFileResponse->getHeaderLine('Content-Type'))
        ->toBe('application/pdf');

});

it('can get file contents on bad response', function () {
    $response = new Response(500, [], null);
    $pdfFileResponse = new PdfFileResponse($response);

    expect($pdfFileResponse->getFileContents())
        ->toBeString()
        ->toBeEmpty();
});
