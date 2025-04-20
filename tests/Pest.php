<?php

use AmaizingCompany\CertifactionClient\Tests\ApiTestCase;
use AmaizingCompany\CertifactionClient\Tests\PackageTestCase;
use AmaizingCompany\CertifactionClient\Tests\TestCase;

// uses(TestCase::class)->in(__DIR__);
uses(ApiTestCase::class)->in(__DIR__.'/Feature/Api');
uses(PackageTestCase::class)
    ->in(
        __DIR__.'/Feature/Models',
        __DIR__.'/Feature/Events',
    );
