<?php

use AmaizingCompany\CertifactionClient\Api\DocumentItem;
use AmaizingCompany\CertifactionClient\Api\Requests\SignatureRequest;
use AmaizingCompany\CertifactionClient\Api\Signer;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Enums\Language;
use AmaizingCompany\CertifactionClient\Enums\SignatureType;
use Illuminate\Support\Collection;

pest()->group('api');

beforeEach(function () {
    $this->signatureRequest = new SignatureRequest;
});

it('can initiate instance', function () {
    expect($this->signatureRequest)
        ->toBeInstanceOf(SignatureRequest::class);
});

it('can handle accept language', function () {
    expect($this->signatureRequest->getAcceptLanguage())
        ->toBeNull()
        ->and($this->signatureRequest->acceptLanguage(Language::DE)->getAcceptLanguage())
        ->toBeInstanceOf(Language::class)
        ->toBe(Language::DE);
});

it('can handle additional page for signature', function () {
    expect($this->signatureRequest->hasAdditionalPageForSignature())
        ->toBeBool()
        ->toBeTrue()
        ->and($this->signatureRequest->additionalPageForSignature(false)->hasAdditionalPageForSignature())
        ->toBeFalse()
        ->and($this->signatureRequest->additionalPageForSignature()->hasAdditionalPageForSignature())
        ->toBeTrue();
});

it('can handle digital twin', function () {
    expect($this->signatureRequest->hasDigitalTwin())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->signatureRequest->digitalTwin()->hasDigitalTwin())
        ->toBeTrue()
        ->and($this->signatureRequest->digitalTwin(false)->hasDigitalTwin())
        ->toBeFalse();
});

it('can handle digital twin qr code page number', function () {
    expect($this->signatureRequest->getDigitalTwinPageNumber())
        ->toBeNull()
        ->and($this->signatureRequest->digitalTwinQrPageNumber(1)->getDigitalTwinPageNumber())
        ->toBeInt()
        ->toBe(1);
});

it('can handle digital twin qr code position', function () {
    expect($this->signatureRequest->getDigitalTwinQrPositionX())
        ->toBeNull()
        ->and($this->signatureRequest->getDigitalTwinQrPositionY())
        ->toBeNull()
        ->and($this->signatureRequest->digitalTwinQrPosition(1, 2)->getDigitalTwinQrPositionX())
        ->toBeInt()
        ->toBe(1)
        ->and($this->signatureRequest->getDigitalTwinQrPositionY())
        ->toBeInt()
        ->toBe(2)
        ->and($this->signatureRequest->digitalTwinQrPosition(1.1, 2.1)->getDigitalTwinQrPositionX())
        ->toBeFloat()
        ->toBe(1.1)
        ->and($this->signatureRequest->getDigitalTwinQrPositionY())
        ->toBeFloat()
        ->toBe(2.1);
});

it('can handle hash', function () {
    expect($this->signatureRequest->getHash())
        ->toBeNull()
        ->and($this->signatureRequest->withHash('TEST')->getHash())
        ->toBeString()
        ->toBe('TEST');
});

it('can handle note', function () {
    expect($this->signatureRequest->getNote())
        ->toBeNull()
        ->and($this->signatureRequest->note('TEST')->getNote())
        ->toBeString()
        ->toBe('TEST');
});

it('can handle pdf-a', function () {
    expect($this->signatureRequest->isPdfA())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->signatureRequest->pdfA()->isPdfA())
        ->toBeTrue()
        ->and($this->signatureRequest->pdfA(false)->isPdfA())
        ->toBeFalse();
});

it('can get query params', function () {
    expect($this->signatureRequest->getQueryParams())
        ->toBeArray()
        ->toBeEmpty();
});

it('can handle jurisdiction', function () {
    expect($this->signatureRequest->getJurisdiction())
        ->toBeNull()
        ->and($this->signatureRequest->jurisdiction(Jurisdiction::EIDAS)->getJurisdiction())
        ->toBeInstanceOf(Jurisdiction::class)
        ->toBe(Jurisdiction::EIDAS);
});

it('can handle prepare option', function () {
    expect($this->signatureRequest->isPrepared())
        ->toBeBool()
        ->toBeTrue()
        ->and($this->signatureRequest->noPrepare()->isPrepared())
        ->toBeFalse()
        ->and($this->signatureRequest->noPrepare(false)->isPrepared())
        ->toBeTrue();
});

it('can handle signature height', function () {
    expect($this->signatureRequest->getSignatureHeight())
        ->toBeNull()
        ->and($this->signatureRequest->signatureHeight(100)->getSignatureHeight())
        ->toBeInt()
        ->toBe(100);
});

it('can handle signature page number', function () {
    expect($this->signatureRequest->getSignaturePageNumber())
        ->toBeNull()
        ->and($this->signatureRequest->signaturePageNumber(1)->getSignaturePageNumber())
        ->toBeInt()
        ->toBe(1);
});

it('can handle signature position', function () {
    expect($this->signatureRequest->getSignaturePositionX())
        ->toBeNull()
        ->and($this->signatureRequest->getSignaturePositionY())
        ->toBeNull()
        ->and($this->signatureRequest->signaturePosition(1, 2)->getSignaturePositionX())
        ->toBeInt()
        ->toBe(1)
        ->and($this->signatureRequest->getSignaturePositionY())
        ->toBeInt()
        ->toBe(2)
        ->and($this->signatureRequest->signaturePosition(1.1, 2.1)->getSignaturePositionX())
        ->toBeFloat()
        ->toBe(1.1)
        ->and($this->signatureRequest->getSignaturePositionY())
        ->toBeFloat()
        ->toBe(2.1);
});

it('can handle signature type', function () {
    expect($this->signatureRequest->getSignatureType())
        ->toBeNull()
        ->and($this->signatureRequest->signatureType(SignatureType::SES)->getSignatureType())
        ->toBeInstanceOf(SignatureType::class)
        ->toBe(SignatureType::SES);
});

it('can handle signer', function () {
    expect($this->signatureRequest->getSigner())
        ->toBeNull()
        ->and($this->signatureRequest->for(new Signer('test@example.com'))->getSigner())
        ->toBeInstanceOf(Signer::class)
        ->and($this->signatureRequest->getQueryParams())
        ->toHaveKey('email');
});

it('possible signer params configured', function () {
    expect($this->signatureRequest->getSignerParams())
        ->toBeArray()
        ->toBe([
            'name',
            'first-name',
            'last-name',
            'email',
            'mobile-phone',
            'citizenship',
            'birthday',
            'gender',
            'domicile',
        ]);
});

it('can handle auto sign', function () {
    expect($this->signatureRequest->hasAutoSign())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->signatureRequest->autoSign()->hasAutoSign())
        ->toBeTrue()
        ->and($this->signatureRequest->autoSign(false)->hasAutoSign())
        ->toBeFalse();
});

it('can handle file name', function () {
    expect($this->signatureRequest->getFileName())
        ->toBeNull()
        ->and($this->signatureRequest->fileName('test.pdf')->getFileName())
        ->toBeString()
        ->toBe('test.pdf');
});

it('can handle signer notification', function () {
    expect($this->signatureRequest->shouldNotifySigner())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->signatureRequest->notifySigner()->shouldNotifySigner())
        ->toBeTrue()
        ->and($this->signatureRequest->notifySigner(false)->shouldNotifySigner())
        ->toBeFalse();
});

it('can handle message', function () {
    expect($this->signatureRequest->getMessage())
        ->toBeNull()
        ->and($this->signatureRequest->message('TEST')->getMessage())
        ->toBeString()
        ->toBe('TEST');
});

it('can handle selective signing', function () {
    expect($this->signatureRequest->hasSelectiveSigning())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->signatureRequest->selectiveSigning()->hasSelectiveSigning())
        ->toBeTrue()
        ->and($this->signatureRequest->selectiveSigning(false)->hasSelectiveSigning())
        ->toBeFalse();
});

it('can handle transaction id', function () {
    expect($this->signatureRequest->getTransactionId())
        ->toBeNull()
        ->and($this->signatureRequest->transactionId('TEST_ID')->getTransactionId())
        ->toBeString()
        ->toBe('TEST_ID');
});

it('can handle webhook url', function () {
    expect($this->signatureRequest->getWebhookUrl())
        ->toBeNull()
        ->and($this->signatureRequest->webhookUrl('https://test.example')->getWebhookUrl())
        ->toBeString()
        ->toBe('https://test.example');
});

it('can handle password', function () {
    expect($this->signatureRequest->hasPassword())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->signatureRequest->getPasswordEncryption())
        ->toBeNull()
        ->and($this->signatureRequest->getEncryptionKey())
        ->toBeNull()
        ->and($this->signatureRequest->withPassword('TEST')->hasPassword())
        ->toBeTrue()
        ->and($this->signatureRequest->getEncryptionKey())
        ->toBeString()
        ->toBe('TEST')
        ->and($this->signatureRequest->getPasswordEncryption())
        ->toBeString()
        ->toBe('xor-b58');
});

it('can handle documents', function () {
    expect($this->signatureRequest->getDocuments())
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty()
        ->and($this->signatureRequest->addDocument(new DocumentItem('https://test.example', 'test.pdf'))->getDocuments())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(1)
        ->and($this->signatureRequest->addDocuments(
            new DocumentItem('https://test.example', 'test.pdf'),
            new DocumentItem('https://test.example', 'test.pdf')
        )->getDocuments())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(3);
});
