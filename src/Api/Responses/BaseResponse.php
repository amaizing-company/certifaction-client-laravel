<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

use AmaizingCompany\CertifactionClient\Api\Contracts\CertifactionResponse;
use Illuminate\Http\Client\Response;
use Psr\Http\Message\MessageInterface;

/**
 * @method Response getResponse()
 */
abstract class BaseResponse extends Response
{
    public function __construct(MessageInterface $response)
    {
        parent::__construct($response);

        $this->boot();
    }

    protected function boot(): void
    {
        //
    }
}
