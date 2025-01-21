<?php

namespace AmaizingCompany\CertifactionClient\Api;

use AmaizingCompany\CertifactionClient\Enums\DocumentStatus;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Enums\SignatureTransactionStatus;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class EnvelopeItem
{
    protected string $legalWeight;
    protected ?Jurisdiction $jurisdiction;
    protected ?DocumentStatus $status;
    protected ?Carbon $signedAt;
    protected string $fileUrl;
    protected string $fileId;
    protected array $comments;

    public function __construct(array $data)
    {
        $this
            ->setLegalWeight(Arr::get($data, 'legal_weight', ""))
            ->setJurisdiction(Arr::get($data, 'jurisdiction', ""))
            ->setStatus(Arr::get($data, 'status', ""))
            ->setSignedAt(Arr::get($data, 'signed_at', ""))
            ->setFileUrl(Arr::get($data, 'file_url', ""))
            ->setFileId(Arr::get($data, 'file_id', ""))
            ->setComments(Arr::get($data, 'comments', []));
    }

    public function getComments(): array
    {
        return $this->comments;
    }

    public function getJurisdiction(): ?Jurisdiction
    {
        return $this->jurisdiction;
    }

    public function getFileId(): string
    {
        return $this->fileId;
    }

    public function getFileUrl(): string
    {
        return $this->fileUrl;
    }

    public function getLegalWeight(): string
    {
        return $this->legalWeight;
    }

    public function getSignedAt(): ?Carbon
    {
        return $this->signedAt;
    }

    public function getStatus(): ?DocumentStatus
    {
        return $this->status;
    }



    public function isSigned(): bool
    {
        return DocumentStatus::SIGNED === $this->getStatus();
    }

    protected function setLegalWeight(string $legalWeight): static
    {
        $this->legalWeight = $legalWeight;

        return $this;
    }

    protected function setJurisdiction(?string $jurisdiction): static
    {
        if (!empty($jurisdiction)) {
            $this->jurisdiction = Jurisdiction::tryFrom($jurisdiction);
        }

        return $this;
    }

    protected function setStatus(string $status): static
    {
        if (!empty($status)) {
            $this->status = DocumentStatus::tryFrom($status);
        }

        return $this;
    }

    protected function setSignedAt(string $datetime): static
    {
        if (!empty($datetime)) {
            $this->signedAt = Carbon::create($datetime);
        }

        return $this;
    }

    protected function setFileUrl(string $url): static
    {
        $this->fileUrl = $url;

        return $this;
    }

    protected function setFileId(string $id): static
    {
        $this->fileId = $id;

        return $this;
    }

    protected function setComments(array $comments): static
    {
        $this->comments = $comments;

        return $this;
    }
}
