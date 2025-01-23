<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Contracts\CertifactionResponse;
use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\AccountIdentificationResponse;
use AmaizingCompany\CertifactionClient\CertifactionClient;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Enums\DocumentType;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;

final class StartAccountIdentificationRequest implements Request
{
    public function __construct(
        protected string $email,
        protected string $firstName,
        protected string $lastName,
        protected string $phoneNumber,
        protected DocumentType $documentType,
        protected Jurisdiction $jurisdiction
    ) {}

    public static function make(
        string $email,
        string $firstName,
        string $lastName,
        string $phoneNumber,
        DocumentType $documentType,
        Jurisdiction $jurisdiction
    ): StartAccountIdentificationRequest {
        return new StartAccountIdentificationRequest($email, $firstName, $lastName, $phoneNumber, $documentType, $jurisdiction);
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send(): AccountIdentificationResponse|CertifactionResponse
    {
        $response = CertifactionClient::makeRequest(CertifactionEnvironment::LOCAL)
            ->withBody(
                json_encode([
                    'email' => $this->email,
                    'first_name' => $this->firstName,
                    'last_name' => $this->lastName,
                    'phone_number' => $this->phoneNumber,
                    'document_type' => $this->documentType->name,
                    'jurisdiction' => $this->jurisdiction->name,
                ])
            )
            ->acceptJson()
            ->post('/identity/create');

        return new AccountIdentificationResponse($response->toPsrResponse());
    }
}
