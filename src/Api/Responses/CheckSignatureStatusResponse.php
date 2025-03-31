<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

use AmaizingCompany\CertifactionClient\Api\DataObjects\EnvelopeItem;
use AmaizingCompany\CertifactionClient\Api\DataObjects\Signer;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CheckSignatureStatusResponse extends BaseResponse
{
    protected Collection $envelopeItems;

    protected function boot(): void
    {
        $this->envelopeItems = $this->parseEnvelopeItems($this->json('envelope_items', []));
    }

    public function getSignatureRequestUrl(): ?string
    {
        return $this->json('url');
    }

    public function getSignatureRequestId(): ?string
    {
        return $this->json('id');
    }

    public function getCreatedAt(): ?Carbon
    {
        $datetime = $this->json('created_at');

        if (empty($datetime)) {
            return null;
        }

        return Carbon::create($datetime);
    }

    public function isCancelled(): ?bool
    {
        return $this->json('cancelled');
    }

    /**
     * @return Collection<EnvelopeItem>
     */
    public function getSignedEnvelopeItems(): Collection
    {
        return $this->envelopeItems->filter(fn (EnvelopeItem $item) => $item->isSigned());
    }

    /**
     * @return Collection<EnvelopeItem>
     */
    public function getUnsignedEnvelopeItems(): Collection
    {
        return $this->envelopeItems->filter(fn (EnvelopeItem $item) => ! $item->isSigned());
    }

    public function getSigner(): ?Signer
    {
        $signer = $this->json('signer');

        if (empty($signer) || empty(Arr::get($signer, 'email'))) {
            return null;
        }

        return new Signer($this->json('signer')['email']);
    }

    public function hasAllItemsSigned(): bool
    {
        if ($this->envelopeItems->isEmpty()) {
            return false;
        }

        return $this->envelopeItems->count() === $this->getSignedEnvelopeItems()->count();
    }

    public function hasAllItemsUnsigned(): bool
    {
        if ($this->envelopeItems->isEmpty()) {
            return true;
        }

        return $this->envelopeItems->count() === $this->getUnsignedEnvelopeItems()->count();
    }

    public function hasUnsignedItems(): bool
    {
        return $this->getUnsignedEnvelopeItems()->count() > 0;
    }

    protected function parseEnvelopeItems(array $items): Collection
    {
        $envelopeItems = Collection::empty();

        foreach ($items as $item) {
            $envelopeItems->add(
                new EnvelopeItem($item)
            );
        }

        return $envelopeItems;
    }

    public function getEnvelopeItems(): Collection
    {
        return $this->envelopeItems;
    }
}
