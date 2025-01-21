<?php

namespace AmaizingCompany\CertifactionClient;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CertifactionClientServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('certifaction-client-laravel')
            ->hasConfigFile();
    }
}
