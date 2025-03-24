<?php

namespace AmaizingCompany\CertifactionClient\Contracts;

use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Enums\SignatureType;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface Signable
{
    /**
     * Relation to certifaction documents model.
     *
     * @return HasMany
     */
    public function certifactionDocuments(): HasMany;

    /**
     * Get file name.
     *
     * @return string
     */
    public function getDocumentName(): string;

    /**
     * The encryption key with that the document was encrypted if it uses an encryption.
     * Make sure that the withPassword method returns true if you want use this option.
     *
     * @return string|null
     */
    public function getEncryptionKey(): ?string;

    /**
     * Get raw file contents.
     *
     * @return string
     */
    public function getFileContents(): string;

    /**
     * Get user model that should act as signer.
     *
     * @return CertifactionUser
     */
    public function getSigner(): CertifactionUser;

    /**
     * Get the webhook url that will be called if a signature request was finished.
     *
     * @return string
     */
    public function getWebhookUrl(): string;

    /**
     * Determine if the qr-code for the digital twin and the signature should add to an additional page.
     *
     * @return bool
     */
    public function hasAdditionalPage(): bool;

    /**
     * Determine if certifaction should store a digital twin of the document in there cloud.
     *
     * @return bool
     */
    public function hasDigitalTwin(): bool;

    /**
     * Determine if the document is in PDF-A format.
     *
     * @return bool
     */
    public function isPdfA(): bool;

    /**
     * Determine the jurisdiction to sign the document.
     *
     * @return Jurisdiction
     */
    public function jurisdiction(): Jurisdiction;

    /**
     * Determine the type of signature request for the document type.
     *
     * @return SignatureType
     */
    public function legalWeight(): SignatureType;

    /**
     * The x coordinate on where the qr code for digital twin should be placed.
     *
     * @return int
     */
    public function qrCodePositionX(): int;

    /**
     * The x coordinate on where the qr code for digital twin should be placed.
     *
     * @return int
     */
    public function qrCodePositionY(): int;

    /**
     * The height of the qr code for digital twin.
     *
     * @return int
     */
    public function qrCodeHeight(): int;

    /**
     * The page number on where the qr code for the digital twin should be placed.
     *
     * @return int
     */
    public function qrCodePageNumber(): int;

    /**
     * Determine if the signer should be notified if a new signature request within this document type was created.
     *
     * @return bool
     */
    public function shouldNotifySigner(): bool;

    /**
     * The x coordinate on where the signature should be placed.
     *
     * @return int
     */
    public function signaturePositionX(): int;

    /**
     * The y coordinate on where the signature should be placed.
     *
     * @return int
     */
    public function signaturePositionY(): int;

    /**
     * The height of the signature.
     *
     * @return int
     */
    public function signatureHeight(): int;

    /**
     * The page number on where the signature should be placed.
     *
     * @return int
     */
    public function signaturePageNumber(): int;

    /**
     * The storage disk where a document should be stored if it was downloaded.
     *
     * @return string|null
     */
    public function storageDisk(): ?string;

    /**
     * The storage directory where a document should be stored if it was downloaded.
     *
     * @return string|null
     */
    public function storageDirectory(): ?string;

    /**
     * Determine if the document is encrypted. Make sure that an encryption key is set.
     *
     * @return bool
     */
    public function withPassword(): bool;
}
