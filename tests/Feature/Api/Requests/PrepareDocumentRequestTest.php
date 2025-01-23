<?php

use AmaizingCompany\CertifactionClient\Api\Requests\PrepareDocumentRequest;
use AmaizingCompany\CertifactionClient\Enums\DocumentPrepareScope;
use AmaizingCompany\CertifactionClient\Enums\Language;

pest()->group('api', 'requests');

beforeEach(function () {
    $this->prepareDocumentRequest = new PrepareDocumentRequest($this->getPdfFileContents(), DocumentPrepareScope::SIGN);
});

it('can initiate instance', function () {
    expect($this->prepareDocumentRequest)
        ->toBeInstanceOf(PrepareDocumentRequest::class);
});

it('can handle scope', function () {
    expect($this->prepareDocumentRequest->getScope())
        ->toBeInstanceOf(DocumentPrepareScope::class)
        ->toBe(DocumentPrepareScope::SIGN)
        ->and($this->prepareDocumentRequest->scope(DocumentPrepareScope::CERTIFY)->getScope())
        ->toBe(DocumentPrepareScope::CERTIFY);
});

it('can handle upload option', function () {
    expect($this->prepareDocumentRequest->isUpload())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->prepareDocumentRequest->upload()->isUpload())
        ->toBeTrue()
        ->and($this->prepareDocumentRequest->upload(false)->isUpload())
        ->toBeFalse();
});

it('can handle accept language', function () {
    expect($this->prepareDocumentRequest->getAcceptLanguage())
        ->toBeNull()
        ->and($this->prepareDocumentRequest->acceptLanguage(Language::DE)->getAcceptLanguage())
        ->toBeInstanceOf(Language::class)
        ->toBe(Language::DE);
});

it('can handle additional page for signature', function () {
    expect($this->prepareDocumentRequest->hasAdditionalPageForSignature())
        ->toBeBool()
        ->toBeTrue()
        ->and($this->prepareDocumentRequest->additionalPageForSignature(false)->hasAdditionalPageForSignature())
        ->toBeFalse()
        ->and($this->prepareDocumentRequest->additionalPageForSignature()->hasAdditionalPageForSignature())
        ->toBeTrue();
});

it('can handle digital twin', function () {
    expect($this->prepareDocumentRequest->hasDigitalTwin())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->prepareDocumentRequest->digitalTwin()->hasDigitalTwin())
        ->toBeTrue()
        ->and($this->prepareDocumentRequest->digitalTwin(false)->hasDigitalTwin())
        ->toBeFalse();
});

it('can handle digital twin qr code page number', function () {
    expect($this->prepareDocumentRequest->getDigitalTwinPageNumber())
        ->toBeNull()
        ->and($this->prepareDocumentRequest->digitalTwinQrPageNumber(1)->getDigitalTwinPageNumber())
        ->toBeInt()
        ->toBe(1);
});

it('can handle digital twin qr code position', function () {
    expect($this->prepareDocumentRequest->getDigitalTwinQrPositionX())
        ->toBeNull()
        ->and($this->prepareDocumentRequest->getDigitalTwinQrPositionY())
        ->toBeNull()
        ->and($this->prepareDocumentRequest->digitalTwinQrPosition(1, 2)->getDigitalTwinQrPositionX())
        ->toBeInt()
        ->toBe(1)
        ->and($this->prepareDocumentRequest->getDigitalTwinQrPositionY())
        ->toBeInt()
        ->toBe(2)
        ->and($this->prepareDocumentRequest->digitalTwinQrPosition(1.1, 2.1)->getDigitalTwinQrPositionX())
        ->toBeFloat()
        ->toBe(1.1)
        ->and($this->prepareDocumentRequest->getDigitalTwinQrPositionY())
        ->toBeFloat()
        ->toBe(2.1);
});

it('can handle pdf-a', function () {
    expect($this->prepareDocumentRequest->isPdfA())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->prepareDocumentRequest->pdfA()->isPdfA())
        ->toBeTrue()
        ->and($this->prepareDocumentRequest->pdfA(false)->isPdfA())
        ->toBeFalse();
});
