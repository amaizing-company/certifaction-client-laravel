<?php

namespace AmaizingCompany\CertifactionClient;

use AmaizingCompany\CertifactionClient\Api\Requests\GetOrganizationRequest;
use AmaizingCompany\CertifactionClient\Api\Responses\GetOrganizationResponse;
use AmaizingCompany\CertifactionClient\Contracts\Account;
use AmaizingCompany\CertifactionClient\Enums\DocumentType;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Jobs\ProcessAccountDeletion;
use AmaizingCompany\CertifactionClient\Jobs\ProcessAccountIdentificationRequest;
use AmaizingCompany\CertifactionClient\Jobs\ProcessAccountIdentificationStatusCheck;
use Illuminate\Support\Facades\Cache;

class CertifactionClient
{
    const CACHE_KEY = 'cerifaction-client';

    const CONFIG_KEY = 'certifaction-client-laravel';

    protected static function getApiKey(): string
    {
        return static::getConfig('api.auth.key');
    }

    public function getDefaultRoleId(): string
    {
        return static::getConfig('role_id');
    }

    public static function getOrganization(bool $refresh = false): GetOrganizationResponse
    {
        if ($refresh) {
            Cache::forget('certifaction-client.organization');
        }

        return Cache::rememberForever('certifaction-client.organization', function () {
            return GetOrganizationRequest::make()->send();
        });
    }

    public static function flushCache(?string $key = null): bool
    {
        return Cache::forget(static::getCacheKey($key));
    }

    protected static function getCacheKey(?string $key = null): string
    {
        return static::CACHE_KEY.empty($key) ?: ".$key";
    }

    public static function getConfig(string $key)
    {
        return config(static::CONFIG_KEY.".$key");
    }

    public static function requestAccountIdentification(Account $account, DocumentType $method, ?Jurisdiction $jurisdiction = null): bool
    {
        if ($account->hasPendingIdentificationRequest()) {
            return false;
        }

        $identityTransaction = $account->identityTransactions()->firstOrCreate([
            'status' => IdentificationStatus::INTENT,
            'identification_method' => $method,
        ]);

        ProcessAccountIdentificationRequest::dispatch($identityTransaction, $jurisdiction);

        return true;
    }

    public static function requestAccountIdentificationStatusCheck(Account $account): bool
    {
        $identityTransaction = $account->getPendingIdentityTransaction();

        if (empty($identityTransaction) || $identityTransaction->isFinished() || $identityTransaction->isIntent()) {
            return false;
        }

        ProcessAccountIdentificationStatusCheck::dispatch($identityTransaction);

        return true;
    }

    public static function requestAccountDeletion(Account $account): void
    {
        ProcessAccountDeletion::dispatch($account);
    }
}
