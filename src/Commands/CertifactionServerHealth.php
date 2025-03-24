<?php

namespace AmaizingCompany\CertifactionClient\Commands;

use AmaizingCompany\CertifactionClient\Api\DataObjects\ServiceStatusItem;
use AmaizingCompany\CertifactionClient\Api\Requests\CheckServerHealthRequest;
use AnourValar\EloquentSerialize\Service;
use Illuminate\Console\Command;

class CertifactionServerHealth extends Command
{
    public $signature = 'certifaction:server:health';

    protected $description = 'Check server health status.';

    public function handle()
    {
        $response = CheckServerHealthRequest::make()->send();

        $this->table(
            [
                'Status',
                'Service',
                'Description',
            ],
            $response->getServices()->map(function (ServiceStatusItem $service) {
                return [
                    $service->getStatus(),
                    $service->getServiceName(),
                    $service->getDescription(),
                ];
            })
        );
    }
}
