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
     */
    public static function getApiKey(): string
    {
        return static::getConfig('api.auth.key');
    }

    /**
     * Get the default role id from config.
     */
    public function getDefaultRoleId(): string
    {
        return static::getConfig('role_id');
    }

    /**
     * Get the organization response.
     *
     * @param  bool  $refresh  Refresh the cache.
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
     * @param  string|null  $key  Specific key to flush.
     */
    public static function flushCache(?string $key = null): bool
    {
        return Cache::forget(static::getCacheKey($key));
    }

    /**
     * Get cache value from key.
     *
     * @param  string|null  $key  Cache key.
     */
    protected static function getCacheKey(?string $key = null): string
    {
        return static::CACHE_KEY.empty($key) ?: ".$key";
    }

    /**
     * Get config value from key.
     *
     * @param  string  $key  Config key
     * @return \Illuminate\Config\Repository|\Illuminate\Foundation\Application|mixed|null
     */
    public static function getConfig(string $key)
    {
        return config(static::CONFIG_KEY.".$key");
    }

    /**
     * Request an account identification.
     *
     * @param  Account  $account  Related account model
     * @param  DocumentType  $method  Document type for identification
     * @param  Jurisdiction|null  $jurisdiction  Jurisdiction for identification
     */
    public static function requestAccountIdentification(Account $account, DocumentType $method, ?Jurisdiction $jurisdiction = null, bool $sync = false): bool
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

        if ($sync) {
            ProcessAccountIdentificationRequest::dispatchSync($identityTransaction, $jurisdiction);
        } else {
            ProcessAccountIdentificationRequest::dispatch($identityTransaction, $jurisdiction);
        }

        return true;
    }

    /**
     * Request an account identification status check.
     *
     * @param  Account  $account  Related account model
     */
    public static function requestAccountIdentificationStatusCheck(Account $account, bool $sync = false): bool
    {
        $identityTransaction = $account->getPendingIdentityTransaction();

        if (empty($identityTransaction) || $identityTransaction->isFinished() || $identityTransaction->isIntent()) {
            return false;
        }

        if ($sync) {
            ProcessAccountIdentificationStatusCheck::dispatchSync($identityTransaction);
        } else {
            ProcessAccountIdentificationStatusCheck::dispatch($identityTransaction);
        }

        return true;
    }

    /**
     * Request an account deletion.
     *
     * @param  Account  $account  Related account model
     */
    public static function requestAccountDeletion(Account $account, bool $sync = false): void
    {
        if ($sync) {
            ProcessAccountDeletion::dispatchSync($account);
        } else {
            ProcessAccountDeletion::dispatch($account);
        }
    }

    /**
     * Request to prepare a document.
     */
    public function requestDocumentPreparation(DocumentPrepareScope $scope, Signable $signable, Contracts\SignatureTransaction $transaction, bool $sync = false): void
    {
        if ($sync) {
            ProcessPrepareDocumentRequest::dispatchSync($scope, $signable, $transaction);
        } else {
            ProcessPrepareDocumentRequest::dispatch($scope, $signable, $transaction);
        }
    }

    /**
     * Request a signature based on a signature transaction.
     */
    public function requestSignature(SignatureTransaction $transaction, ?bool $notifySigner = null, bool $sync = false): void
    {
        if ($sync) {
            ProcessSignatureRequest::dispatchSync($transaction, $notifySigner);
        } else {
            ProcessSignatureRequest::dispatch($transaction, $notifySigner);
        }
    }
}
