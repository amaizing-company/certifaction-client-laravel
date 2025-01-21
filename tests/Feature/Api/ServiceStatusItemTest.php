<?php

use AmaizingCompany\CertifactionClient\Api\ServiceStatusItem;

pest()->group('api');

beforeEach(function () {
    $this->serviceStatusItem = new ServiceStatusItem(
        'database',
        'description',
        'UP'
    );
});

it ('can initiate instance', function () {
    expect($this->serviceStatusItem)
        ->toBeInstanceOf(ServiceStatusItem::class);
});

it ('can get service name', function () {
    expect($this->serviceStatusItem->getServiceName())
        ->toBeString()
        ->toBe('database');
});

it ('can get description', function () {
    expect($this->serviceStatusItem->getDescription())
        ->toBeString()
        ->toBe('description');
});

it ('can get status', function () {
    expect($this->serviceStatusItem->getStatus())
        ->toBeString()
        ->toBe('UP');
});

it ('can check if service is healthy', function () {
    expect($this->serviceStatusItem->isHealthy())
        ->toBeBool()
        ->toBeTrue()
        ->and(new ServiceStatusItem('database', 'description', 'DOWN')->isHealthy())
        ->toBeBool()
        ->toBeFalse();
});
