<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

use AmaizingCompany\CertifactionClient\Api\Contracts\CertifactionResponse;
use AmaizingCompany\CertifactionClient\Api\DataObjects\ServiceStatusItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CheckServerHealthResponse extends BaseResponse implements CertifactionResponse
{
    protected Collection $serviceStatusItems;

    protected function boot(): void
    {
        $this->serviceStatusItems = $this->parseServiceStatusItems();
    }

    /**
     * @return Collection<ServiceStatusItem>
     */
    public function getServices(): Collection
    {
        return $this->serviceStatusItems;
    }

    /**
     * @return Collection<ServiceStatusItem>
     */
    public function getHealthyServices(): Collection
    {
        $services = $this->getServices();
        $items = Collection::empty();

        foreach ($services as $service) {
            if ($service->isHealthy()) {
                $items->add($service);
            }
        }

        return $items;
    }

    /**
     * @return Collection<ServiceStatusItem>
     */
    public function getUnhealthyServices(): Collection
    {
        $services = $this->getServices();
        $items = Collection::empty();

        foreach ($services as $service) {
            if (! $service->isHealthy()) {
                $items->add($service);
            }
        }

        return $items;
    }

    public function allServicesHealthy(): bool
    {
        return $this->getUnhealthyServices()->isEmpty() && $this->getServices()->isNotEmpty();
    }

    public function hasUnhealthyServices(): bool
    {
        return $this->getUnhealthyServices()->isNotEmpty();
    }

    protected function parseServiceStatusItems(): Collection
    {
        $items = Collection::empty();
        $responseItems = $this->json();

        if (empty($responseItems)) {
            return $items;
        }

        foreach ($responseItems as $item) {
            $items->add(new ServiceStatusItem(
                Arr::get($item, 'service_name', ''),
                Arr::get($item, 'description', ''),
                Arr::get($item, 'status', 'DOWN'),
            ));
        }

        return $items;
    }
}
