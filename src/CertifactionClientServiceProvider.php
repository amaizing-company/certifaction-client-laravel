<?php

namespace AmaizingCompany\CertifactionClient;

use AmaizingCompany\CertifactionClient\Commands\CertifactionAccountQesStatus;
use AmaizingCompany\CertifactionClient\Commands\CertifactionClearCache;
use AmaizingCompany\CertifactionClient\Commands\CertifactionListRoles;
use AmaizingCompany\CertifactionClient\Commands\CertifactionListUsers;
use AmaizingCompany\CertifactionClient\Commands\CertifactionServerHealth;
use AmaizingCompany\CertifactionClient\Commands\CertifactionServerPing;
use AmaizingCompany\CertifactionClient\Contracts\Account as AccountContract;
use AmaizingCompany\CertifactionClient\Contracts\Document as DocumentContract;
use AmaizingCompany\CertifactionClient\Contracts\Events\AccountDeleted as AccountDeletedContract;
use AmaizingCompany\CertifactionClient\Contracts\Events\DocumentPreparationFailed as DocumentPreparationFailedContract;
use AmaizingCompany\CertifactionClient\Contracts\Events\DocumentPrepared as DocumentPreparedContract;
use AmaizingCompany\CertifactionClient\Contracts\Events\FileTransactionFailed as FileTransactionFailedContract;
use AmaizingCompany\CertifactionClient\Contracts\Events\FileTransactionFinished as FileTransactionFinishedContract;
use AmaizingCompany\CertifactionClient\Contracts\Events\FileTransactionStarted as FileTransactionStartedContract;
use AmaizingCompany\CertifactionClient\Contracts\Events\IdentificationRequestFinished as IdentificationRequestFinishedContract;
use AmaizingCompany\CertifactionClient\Contracts\Events\IdentificationRequestStarted as IdentificationRequestStartedContract;
use AmaizingCompany\CertifactionClient\Contracts\Events\IdentificationStatusCheckFinished as IdentificationStatusCheckFinishedContract;
use AmaizingCompany\CertifactionClient\Contracts\Events\SignatureRequestFailed as SignatureRequestFailedContract;
use AmaizingCompany\CertifactionClient\Contracts\Events\SignatureRequestFinished as SignatureRequestFinishedContract;
use AmaizingCompany\CertifactionClient\Contracts\Events\SignatureRequestStarted as SignatureRequestStartedContract;
use AmaizingCompany\CertifactionClient\Contracts\Events\UserInvitedToCertifaction as UserInvitedToCertifactionContract;
use AmaizingCompany\CertifactionClient\Contracts\Events\UserJoinedCertifaction as UserJoinedCertifactionContract;
use AmaizingCompany\CertifactionClient\Contracts\FileTransaction as FileTransactionContract;
use AmaizingCompany\CertifactionClient\Contracts\IdentityTransaction as IdentityTransactionContract;
use AmaizingCompany\CertifactionClient\Contracts\SignatureTransaction as SignatureTransactionContract;
use AmaizingCompany\CertifactionClient\Events\AccountDeleted;
use AmaizingCompany\CertifactionClient\Events\DocumentPreparationFailed;
use AmaizingCompany\CertifactionClient\Events\DocumentPrepared;
use AmaizingCompany\CertifactionClient\Events\FileTransactionFailed;
use AmaizingCompany\CertifactionClient\Events\FileTransactionFinished;
use AmaizingCompany\CertifactionClient\Events\FileTransactionStarted;
use AmaizingCompany\CertifactionClient\Events\IdentificationRequestFinished;
use AmaizingCompany\CertifactionClient\Events\IdentificationRequestStarted;
use AmaizingCompany\CertifactionClient\Events\IdentificationStatusCheckFinished;
use AmaizingCompany\CertifactionClient\Events\SignatureRequestFailed;
use AmaizingCompany\CertifactionClient\Events\SignatureRequestFinished;
use AmaizingCompany\CertifactionClient\Events\SignatureRequestStarted;
use AmaizingCompany\CertifactionClient\Events\UserInvitedToCertifaction;
use AmaizingCompany\CertifactionClient\Events\UserJoinedCertifaction;
use AmaizingCompany\CertifactionClient\Listeners\CreateAccount;
use AmaizingCompany\CertifactionClient\Models\Account;
use AmaizingCompany\CertifactionClient\Models\Document;
use AmaizingCompany\CertifactionClient\Models\FileTransaction;
use AmaizingCompany\CertifactionClient\Models\IdentityTransaction;
use AmaizingCompany\CertifactionClient\Models\SignatureTransaction;
use AmaizingCompany\CertifactionClient\Observers\AccountObserver;
use AmaizingCompany\CertifactionClient\Observers\FileTransactionObserver;
use AmaizingCompany\CertifactionClient\Observers\IdentityTransactionObserver;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CertifactionClientServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('certifaction-client-laravel')
            ->hasConfigFile()
            ->hasMigrations([
                'create_certifaction_accounts_table',
                'create_certifaction_identity_transactions_table',
                'create_certifaction_documents_table',
                'create_certifaction_signature_transactions_table',
                'create_certifaction_signature_transactions_documents_table',
                'create_certifaction_file_transactions_table',
                'add_original_transaction_id_to_certifaction_file_transactions_table',
            ])
            ->hasCommands([
                CertifactionClearCache::class,
                CertifactionListRoles::class,
                CertifactionListUsers::class,
                CertifactionServerHealth::class,
                CertifactionServerPing::class,
                CertifactionAccountQesStatus::class,
            ])
            ->hasRoute('web');
    }

    public function packageRegistered(): void
    {
        // Bind models
        $this->app->bind(AccountContract::class, Account::class);
        $this->app->bind(DocumentContract::class, Document::class);
        $this->app->bind(FileTransactionContract::class, FileTransaction::class);
        $this->app->bind(IdentityTransactionContract::class, IdentityTransaction::class);
        $this->app->bind(SignatureTransactionContract::class, SignatureTransaction::class);

        // Bind events
        $this->app->bind(AccountDeletedContract::class, AccountDeleted::class);
        $this->app->bind(DocumentPreparationFailedContract::class, DocumentPreparationFailed::class);
        $this->app->bind(DocumentPreparedContract::class, DocumentPrepared::class);
        $this->app->bind(FileTransactionFailedContract::class, FileTransactionFailed::class);
        $this->app->bind(FileTransactionFinishedContract::class, FileTransactionFinished::class);
        $this->app->bind(FileTransactionStartedContract::class, FileTransactionStarted::class);
        $this->app->bind(IdentificationRequestFinishedContract::class, IdentificationRequestFinished::class);
        $this->app->bind(IdentificationRequestStartedContract::class, IdentificationRequestStarted::class);
        $this->app->bind(IdentificationStatusCheckFinishedContract::class, IdentificationStatusCheckFinished::class);
        $this->app->bind(SignatureRequestFailedContract::class, SignatureRequestFailed::class);
        $this->app->bind(SignatureRequestFinishedContract::class, SignatureRequestFinished::class);
        $this->app->bind(SignatureRequestStartedContract::class, SignatureRequestStarted::class);
        $this->app->bind(UserInvitedToCertifactionContract::class, UserInvitedToCertifaction::class);
        $this->app->bind(UserJoinedCertifactionContract::class, UserJoinedCertifaction::class);
    }

    public function packageBooted(): void
    {
        $this->registerListeners();
        $this->registerObservers();
        $this->registerRouteBindings();
    }

    public function registerListeners(): void
    {
        Event::listen(UserInvitedToCertifaction::class, CreateAccount::class);
    }

    public function registerObservers(): void
    {
        app(AccountContract::class)::observe(AccountObserver::class);
        app(FileTransactionContract::class)::observe(FileTransactionObserver::class);
        app(IdentityTransactionContract::class)::observe(IdentityTransactionObserver::class);
    }

    public function registerRouteBindings(): void
    {
        Route::bind('signatureTransaction', function (string $value) {
            try {
                $id = Crypt::decrypt($value);
            } catch (\Throwable $e) {
                abort(404);
            }

            return app(SignatureTransactionContract::class)::query()->findOrFail($id);
        });
    }
}
