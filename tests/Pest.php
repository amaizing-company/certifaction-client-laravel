<?php

use AmaizingCompany\CertifactionClient\Tests\ApiTestCase;
use AmaizingCompany\CertifactionClient\Tests\CommandTestCase;
use AmaizingCompany\CertifactionClient\Tests\TestCase;

// uses(TestCase::class)->in(__DIR__);
uses(ApiTestCase::class)->in(__DIR__.'/Feature/Api');
uses(CommandTestCase::class)->in(__DIR__.'/Feature/Commands');
