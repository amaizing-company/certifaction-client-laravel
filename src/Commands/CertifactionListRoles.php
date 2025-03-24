<?php

namespace AmaizingCompany\CertifactionClient\Commands;

use AmaizingCompany\CertifactionClient\CertifactionClient;
use Illuminate\Console\Command;

class CertifactionListRoles extends Command
{
    public $signature = 'certifaction:roles:list
                        {--remote : Fetch roles from remote server}';

    public $description = 'List all available roles of organization.';

    public function handle(): int
    {
        $roles = CertifactionClient::getOrganization($this->option('remote'))->getRoles();

        $this->table(
            ['name', 'id'],
            $roles->map(fn ($role) => [$role->getName(), $role->getId()])
        );

        return self::SUCCESS;
    }
}
