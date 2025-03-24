<?php

namespace AmaizingCompany\CertifactionClient\Commands;

use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;
use Illuminate\Console\Command;

class CertifactionClearCache extends Command
{
    protected $signature = 'certifaction:cache:clear
                            {--key= : Specify a cache key that should be cleared}';

    protected $description = 'Clear cache for certifaction client.';

    public function handle(): int
    {
        $status = CertifactionClient::flushCache($this->option('key'));

        if ($status) {
            $this->info('Cache cleared.');
        }

        return $status ? self::SUCCESS : self::FAILURE;
    }
}
