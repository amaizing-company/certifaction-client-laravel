<?php

namespace AmaizingCompany\CertifactionClient\Commands;

use AmaizingCompany\CertifactionClient\Api\DataObjects\QesStatusItem;
use AmaizingCompany\CertifactionClient\Api\Requests\CheckAccountQesStatusRequest;
use Illuminate\Console\Command;

class CertifactionAccountQesStatus extends Command
{
    protected $signature = 'certifaction:account:qes-status {mobileNumber : Mobile number from connected account}';

    protected $description = 'Check QES status for an account.';

    public function handle()
    {
        $response = new CheckAccountQesStatusRequest($this->argument('mobileNumber'))->send();

        $this->table([
            'Jurisdiction',
            'Signature level',
            'Status',
        ], $response->getQesStatusItems()->map(function (QesStatusItem $item) {
            return [
                $item->getJurisdiction()->value,
                $item->getSignatureLevel(),
                $item->getStatus(),
            ];
        }));
    }
}
