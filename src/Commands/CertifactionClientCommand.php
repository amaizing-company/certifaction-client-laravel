<?php

namespace AmaizingCompany\CertifactionClient\Commands;

use Illuminate\Console\Command;

class CertifactionClientCommand extends Command
{
    public $signature = 'certifaction-client-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
