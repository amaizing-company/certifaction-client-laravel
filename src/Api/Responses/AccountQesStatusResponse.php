<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

use AmaizingCompany\CertifactionClient\Api\DataObjects\QesStatusItem;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use Illuminate\Support\Collection;

class AccountQesStatusResponse extends BaseResponse
{
    protected Collection $qesStatusItems;

    protected function boot(): void
    {
        $this->parseQesStatusItems();
    }

    public function getQesStatusItems(): Collection
    {
        return $this->qesStatusItems;
    }

    public function getQesStatusItemByJurisdiction(string|Jurisdiction $jurisdiction): ?QesStatusItem
    {
        if (is_string($jurisdiction)) {
            $jurisdiction = Jurisdiction::tryFrom($jurisdiction);
        }

        return $this->qesStatusItems->first(function (QesStatusItem $item) use ($jurisdiction) {
            return $item->getJurisdiction() === $jurisdiction;
        });
    }

    public function hasQesStatusItems(): bool
    {
        return $this->qesStatusItems->isNotEmpty();
    }

    protected function parseQesStatusItems(): void
    {
        $items = $this->json('StatusForSigningResponse', []);
        $this->qesStatusItems = Collection::empty();

        foreach ($items as $item) {
            $item = Collection::make($item);

            $this->qesStatusItems->add(new QesStatusItem(
                $item->get('jurisdiction'),
                $item->get('status'),
                $item->get('signatureLevel')
            ));
        }
    }
}
