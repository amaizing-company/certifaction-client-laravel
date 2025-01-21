<?php

namespace AmaizingCompany\CertifactionClient\Enums;

use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;

use function Orchestra\Testbench\remote;

enum CertifactionEnvironment: string
{
    case LOCAL = 'local';
    case ADMIN = 'admin';

    /**
     * @throws ApiServerUriMissingException
     */
    public function getUri(?string $endpoint = null): string
    {
        $uri = config("certifaction-client-laravel.api.environments." . $this->value);

        if (empty($uri)) {
            throw new ApiServerUriMissingException();
        }

        return $uri . $endpoint;
    }
}
