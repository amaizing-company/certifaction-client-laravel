<?php

namespace AmaizingCompany\CertifactionClient\Facades;

use AmaizingCompany\CertifactionClient\Api\Responses\GetOrganizationResponse;
use AmaizingCompany\CertifactionClient\Contracts\Account;
use AmaizingCompany\CertifactionClient\Enums\DocumentType;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use Illuminate\Support\Facades\Facade;

/**
 * @see \AmaizingCompany\CertifactionClient\CertifactionClient
 *
 * @method static bool flushCache(?string $key = null)
 * @method static string getApiKey()
 * @method static mixed getConfig(string $key)
 * @method static string getDefaultRoleId()
 * @method static GetOrganizationResponse getOrganization(bool $refresh = false)
 * @method static bool requestAccountIdentification(Account $account, DocumentType $method, ?Jurisdiction $jurisdiction = null)
 * @method static bool requestAccountIdentificationStatusCheck(Account $account)
 * @method static void requestAccountDeletion(Account $account)
 */
class CertifactionClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \AmaizingCompany\CertifactionClient\CertifactionClient::class;
    }
}
