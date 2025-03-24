<?php

namespace AmaizingCompany\CertifactionClient\Commands;

use AmaizingCompany\CertifactionClient\Api\Requests\ServerPingRequest;
use Illuminate\Console\Command;
use Illuminate\Console\Events\CommandStarting;
use Laravel\Prompts\Spinner;

class CertifactionServerPing extends Command
{
    protected $signature = 'certifaction:server:ping';
    protected $description = 'Check server is responding with ping.';

    public function handle()
    {
        $start = microtime(true);

        $this->line('Pinging...');
        $this->newLine();

        $response = ServerPingRequest::make()->send();

        $end = microtime(true);

        if ($response->successful()) {
            $this->info('Server is responding.');
        } else {
            $this->error('Server is not responding.');
            $this->error('Status: ' . $response->status());
        }

        $this->line('Ping: ' . round(($end - $start)*1000) . 'ms');
        $this->newLine(2);
    }
}
