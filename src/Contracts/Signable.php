<?php

namespace AmaizingCompany\CertifactionClient\Contracts;

use AmaizingCompany\CertifactionClient\Enums\DocumentPrepareScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin Model
 */
interface Signable
{
    /**
     * Relation to certifaction documents model.
     */
    public function certifactionDocuments(): MorphMany;

    /**
     * Get file name.
     */
    public function getDocumentName(): string;

    /**
     * The encryption key with that the document was encrypted if it uses an encryption.
     * Make sure that the withPassword method returns true if you want use this option.
     */
    public function getEncryptionKey(): ?string;

    /**
     * Get raw file contents.
     */
    public function getFileContents(): string;

    /**
     * Get the webhook url that will be called if a signature request was finished.
     */
    public function getWebhookUrl(): string;

    /**
     * Determine if the qr-code for the digital twin and the signature should add to an additional page.
     */
    public function hasAdditionalPage(): bool;

    /**
     * Determine if certifaction should store a digital twin of the document in there cloud.
     */
    public function hasDigitalTwin(): bool;

    /**
     * Determine if the document is in PDF-A format.
     */
    public function isPdfA(): bool;

    /**
     * Request to prepare the document.
     *
     * @return $this
     */
    public function requestDocumentPreparation(DocumentPrepareScope $scope, SignatureTransaction $transaction, bool $sync = false): static;

    /**
     * The x coordinate on where the qr code for digital twin should be placed.
     */
    public function qrCodePositionX(): int;

    /**
     * The x coordinate on where the qr code for digital twin should be placed.
     */
    public function qrCodePositionY(): int;

    /**
     * The height of the qr code for digital twin.
     */
    public function qrCodeHeight(): int;

    /**
     * The page number on where the qr code for the digital twin should be placed.
     */
    public function qrCodePageNumber(): int;

    /**
     * The x coordinate on where the signature should be placed.
     */
    public function signaturePositionX(): int;

    /**
     * The y coordinate on where the signature should be placed.
     */
    public function signaturePositionY(): int;

    /**
     * The height of the signature.
     */
    public function signatureHeight(): int;

    /**
     * The page number on where the signature should be placed.
     */
    public function signaturePageNumber(): int;

    /**
     * The storage disk where a document should be stored if it was downloaded.
     */
    public function storageDisk(): ?string;

    /**
     * The storage directory where a document should be stored if it was downloaded.
     */
    public function storageDirectory(): ?string;

    /**
     * Determine if the document is encrypted. Make sure that an encryption key is set.
     */
    public function withPassword(): bool;
}
