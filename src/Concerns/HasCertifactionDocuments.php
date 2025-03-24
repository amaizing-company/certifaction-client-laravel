<?php

namespace AmaizingCompany\CertifactionClient\Concerns;

use AmaizingCompany\CertifactionClient\Contracts\Document;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @phpstan-ignore trait.unused
 */
trait HasCertifactionDocuments
{
    public function certifactionDocuments(): HasMany
    {
        return $this->hasMany(app(Document::class));
    }

    public function getCertifactionPassword(): ?string
    {
        return null;
    }

    public function getEncryptionKey(): ?string
    {
        return null;
    }

    public function getWebhookUrl(): string
    {
        return CertifactionClient::getConfig('webhook_url');
    }

    public function hasAdditionalPage(): bool
    {
        return false;
    }

    public function hasDigitalTwin(): bool
    {
        return CertifactionClient::getConfig('digital_twin');
    }

    public function isPdfA(): bool
    {
        return CertifactionClient::getConfig('pdf_a');
    }

    public function jurisdiction(): Jurisdiction
    {
        return CertifactionClient::getConfig('jurisdiction');
    }

    public function qrCodePositionX(): int
    {
        return 0;
    }

    public function qrCodePositionY(): int
    {
        return 0;
    }

    public function qrCodeHeight(): int
    {
        return 50;
    }

    public function qrCodePageNumber(): int
    {
        return 1;
    }

    public function shouldNotifySigner(): bool
    {
        return CertifactionClient::getConfig('notify_signer');
    }

    public function signaturePositionX(): int
    {
        return 0;
    }

    public function signaturePositionY(): int
    {
        return 0;
    }

    public function signatureHeight(): int
    {
        return 50;
    }

    public function signaturePageNumber(): int
    {
        return 1;
    }

    public function storageDisk(): ?string
    {
        return null;
    }

    public function storageDirectory(): ?string
    {
        return null;
    }

    public function withPassword(): bool
    {
        return false;
    }
}
