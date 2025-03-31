<?php

namespace AmaizingCompany\CertifactionClient;

use AmaizingCompany\CertifactionClient\Api\Requests\GetOrganizationRequest;
use AmaizingCompany\CertifactionClient\Api\Responses\GetOrganizationResponse;
use AmaizingCompany\CertifactionClient\Contracts\Account;
use AmaizingCompany\CertifactionClient\Contracts\IdentityTransaction;
use AmaizingCompany\CertifactionClient\Contracts\Signable;
use AmaizingCompany\CertifactionClient\Contracts\SignatureTransaction;
use AmaizingCompany\CertifactionClient\Enums\DocumentPrepareScope;
use AmaizingCompany\CertifactionClient\Enums\DocumentType;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Jobs\ProcessAccountDeletion;
use AmaizingCompany\CertifactionClient\Jobs\ProcessAccountIdentificationRequest;
use AmaizingCompany\CertifactionClient\Jobs\ProcessAccountIdentificationStatusCheck;
use AmaizingCompany\CertifactionClient\Jobs\ProcessPrepareDocumentRequest;
use AmaizingCompany\CertifactionClient\Jobs\ProcessSignatureRequest;
use Illuminate\Support\Facades\Cache;

class CertifactionClient
{
    const CACHE_KEY = 'cerifaction-client';

    const CONFIG_KEY = 'certifaction-client-laravel';

    /**
     * Get API authentication key.
     *
     * @return string
     */
    protected static function getApiKey(): string
    {
        return static::getConfig('api.auth.key');
    }

    /**
     * Get the default role id from config.
     *
     * @return string
     */
    public function getDefaultRoleId(): string
    {
        return static::getConfig('role_id');
    }

    /**
     * Get the organization response.
     *
     * @param bool $refresh Refresh the cache.
     * @return GetOrganizationResponse
     */
    public static function getOrganization(bool $refresh = false): GetOrganizationResponse
    {
        if ($refresh) {
            Cache::forget('certifaction-client.organization');
        }

        return Cache::rememberForever('certifaction-client.organization', function () {
            return GetOrganizationRequest::make()->send();
        });
    }

    /**
     * Flush the package cache.
     *
     * @param string|null $key Specific key to flush.
     * @return bool
     */
    public static function flushCache(?string $key = null): bool
    {
        return Cache::forget(static::getCacheKey($key));
    }

    /**
     * Get cache value from key.
     *
     * @param string|null $key Cache key.
     * @return string
     */
    protected static function getCacheKey(?string $key = null): string
    {
        return static::CACHE_KEY.empty($key) ?: ".$key";
    }

    /**
     * Get config value from key.
     *
     * @param string $key Config key
     * @return \Illuminate\Config\Repository|\Illuminate\Foundation\Application|mixed|null
     */
    public static function getConfig(string $key)
    {
        return config(static::CONFIG_KEY.".$key");
    }

    /**
     * Request an account identification.
     *
     * @param Account $account Related account model
     * @param DocumentType $method Document type for identification
     * @param Jurisdiction|null $jurisdiction Jurisdiction for identification
     * @return bool
     */
    public static function requestAccountIdentification(Account $account, DocumentType $method, ?Jurisdiction $jurisdiction = null): bool
    {
        if ($account->hasPendingIdentificationRequest()) {
            return false;
        }

        /**
         * @var IdentityTransaction $identityTransaction
         */
        $identityTransaction = $account->identityTransactions()->firstOrCreate([
            'status' => IdentificationStatus::INTENT,
            'identification_method' => $method,
        ]);

        ProcessAccountIdentificationRequest::dispatch($identityTransaction, $jurisdiction);

        return true;
    }

    /**
     * Request an account identification status check.
     *
     * @param Account $account Related account model
     * @return bool
     */
    public static function requestAccountIdentificationStatusCheck(Account $account): bool
    {
        $identityTransaction = $account->getPendingIdentityTransaction();

        if (empty($identityTransaction) || $identityTransaction->isFinished() || $identityTransaction->isIntent()) {
            return false;
        }

        ProcessAccountIdentificationStatusCheck::dispatch($identityTransaction);

        return true;
    }

    /**
     * Request an account deletion.
     *
     * @param Account $account Related account model
     * @return void
     */
    public static function requestAccountDeletion(Account $account): void
    {
        ProcessAccountDeletion::dispatch($account);
    }

    /**
     * Request to prepare a document.
     *
     * @param DocumentPrepareScope $scope
     * @param Signable $signable
     * @return void
     */
    public function requestDocumentPreparation(DocumentPrepareScope $scope, Signable $signable, Contracts\SignatureTransaction $transaction): void
    {
        ProcessPrepareDocumentRequest::dispatch($scope, $signable, $transaction);
    }

    /**
     * Request a signature based on a signature transaction.
     *
     * @param SignatureTransaction $transaction
     * @param bool|null $notifySigner
     * @return void
     */
    public function requestSignature(SignatureTransaction $transaction, ?bool $notifySigner = null): void
    {
        ProcessSignatureRequest::dispatch($transaction, $notifySigner);
    }
}
