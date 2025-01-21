<?php

use AmaizingCompany\CertifactionClient\Api\Requests\SignDocumentRequest;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Enums\Language;
use AmaizingCompany\CertifactionClient\Enums\SignatureType;

pest()->group('api');

beforeEach(function () {
    $this->signDocumentRequest = new SignDocumentRequest($this->getPdfFileContents());
});

it ('can initiate instance', function () {
    expect($this->signDocumentRequest)
        ->toBeInstanceOf(SignDocumentRequest::class);
});

it ('can handle accept language', function () {
    expect($this->signDocumentRequest->getAcceptLanguage())
        ->toBeNull()
        ->and($this->signDocumentRequest->acceptLanguage(Language::DE)->getAcceptLanguage())
        ->toBeInstanceOf(Language::class)
        ->toBe(Language::DE);
});

it ('can handle additional page for signature', function () {
    expect($this->signDocumentRequest->hasAdditionalPageForSignature())
        ->toBeBool()
        ->toBeTrue()
        ->and($this->signDocumentRequest->additionalPageForSignature(false)->hasAdditionalPageForSignature())
        ->toBeFalse()
        ->and($this->signDocumentRequest->additionalPageForSignature()->hasAdditionalPageForSignature())
        ->toBeTrue();
});

it ('can handle digital twin', function () {
    expect($this->signDocumentRequest->hasDigitalTwin())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->signDocumentRequest->digitalTwin()->hasDigitalTwin())
        ->toBeTrue()
        ->and($this->signDocumentRequest->digitalTwin(false)->hasDigitalTwin())
        ->toBeFalse();
});

it ('can handle digital twin qr code page number', function () {
    expect($this->signDocumentRequest->getDigitalTwinPageNumber())
        ->toBeNull()
        ->and($this->signDocumentRequest->digitalTwinQrPageNumber(1)->getDigitalTwinPageNumber())
        ->toBeInt()
        ->toBe(1);
});

it ('can handle digital twin qr code position', function () {
    expect($this->signDocumentRequest->getDigitalTwinQrPositionX())
        ->toBeNull()
        ->and($this->signDocumentRequest->getDigitalTwinQrPositionY())
        ->toBeNull()
        ->and($this->signDocumentRequest->digitalTwinQrPosition(1,2)->getDigitalTwinQrPositionX())
        ->toBeInt()
        ->toBe(1)
        ->and($this->signDocumentRequest->getDigitalTwinQrPositionY())
        ->toBeInt()
        ->toBe(2)
        ->and($this->signDocumentRequest->digitalTwinQrPosition(1.1, 2.1)->getDigitalTwinQrPositionX())
        ->toBeFloat()
        ->toBe(1.1)
        ->and($this->signDocumentRequest->getDigitalTwinQrPositionY())
        ->toBeFloat()
        ->toBe(2.1);
});

it ('can handle encryption', function () {
    expect($this->signDocumentRequest->getEncryptionKey())
        ->toBeNull()
        ->and($this->signDocumentRequest->getPasswordEncryption())
        ->toBeNull()
        ->and($this->signDocumentRequest->withEncryption('TEST')->getEncryptionKey())
        ->toBeString()
        ->toBe('TEST')
        ->and($this->signDocumentRequest->getPasswordEncryption())
        ->toBeString()
        ->toBe('xor-b58');
});

it ('can handle file', function () {
    expect($this->signDocumentRequest->getFileContents())
        ->toBeString()
        ->toBe($this->getPdfFileContents());
});

it ('can handle filename', function () {
    expect($this->signDocumentRequest->getFileName())
        ->toBeNull()
        ->and($this->signDocumentRequest->fileName('test.pdf')->getFileName())
        ->toBeString()
        ->toBe('test.pdf');
});

it ('can handle hash', function () {
    expect($this->signDocumentRequest->getHash())
        ->toBeNull()
        ->and($this->signDocumentRequest->withHash('TEST')->getHash())
        ->toBeString()
        ->toBe('TEST');
});

it ('can handle note', function () {
    expect($this->signDocumentRequest->getNote())
        ->toBeNull()
        ->and($this->signDocumentRequest->note('TEST')->getNote())
        ->toBeString()
        ->toBe('TEST');
});

it ('can handle pdf-a', function () {
    expect($this->signDocumentRequest->isPdfA())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->signDocumentRequest->pdfA()->isPdfA())
        ->toBeTrue()
        ->and($this->signDocumentRequest->pdfA(false)->isPdfA())
        ->toBeFalse();
});

it ('can get query params', function () {
    expect($this->signDocumentRequest->getQueryParams())
        ->toBeArray()
        ->toBeEmpty();
});

it ('can handle jurisdiction', function () {
    expect($this->signDocumentRequest->getJurisdiction())
        ->toBeNull()
        ->and($this->signDocumentRequest->jurisdiction(Jurisdiction::EIDAS)->getJurisdiction())
        ->toBeInstanceOf(Jurisdiction::class)
        ->toBe(Jurisdiction::EIDAS);
});

it ('can handle prepare option', function () {
    expect($this->signDocumentRequest->isPrepared())
        ->toBeBool()
        ->toBeTrue()
        ->and($this->signDocumentRequest->noPrepare()->isPrepared())
        ->toBeFalse()
        ->and($this->signDocumentRequest->noPrepare(false)->isPrepared())
        ->toBeTrue();
});

it ('can handle signature height', function () {
    expect($this->signDocumentRequest->getSignatureHeight())
        ->toBeNull()
        ->and($this->signDocumentRequest->signatureHeight(100)->getSignatureHeight())
        ->toBeInt()
        ->toBe(100);
});

it ('can handle signature page number', function () {
    expect($this->signDocumentRequest->getSignaturePageNumber())
        ->toBeNull()
        ->and($this->signDocumentRequest->signaturePageNumber(1)->getSignaturePageNumber())
        ->toBeInt()
        ->toBe(1);
});

it ('can handle signature position', function () {
    expect($this->signDocumentRequest->getSignaturePositionX())
        ->toBeNull()
        ->and($this->signDocumentRequest->getSignaturePositionY())
        ->toBeNull()
        ->and($this->signDocumentRequest->signaturePosition(1, 2)->getSignaturePositionX())
        ->toBeInt()
        ->toBe(1)
        ->and($this->signDocumentRequest->getSignaturePositionY())
        ->toBeInt()
        ->toBe(2)
        ->and($this->signDocumentRequest->signaturePosition(1.1, 2.1)->getSignaturePositionX())
        ->toBeFloat()
        ->toBe(1.1)
        ->and($this->signDocumentRequest->getSignaturePositionY())
        ->toBeFloat()
        ->toBe(2.1);
});

it ('can handle signature type', function () {
    expect($this->signDocumentRequest->getSignatureType())
        ->toBeNull()
        ->and($this->signDocumentRequest->signatureType(SignatureType::SES)->getSignatureType())
        ->toBeInstanceOf(SignatureType::class)
        ->toBe(SignatureType::SES);
});
