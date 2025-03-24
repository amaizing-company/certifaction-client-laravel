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
use AmaizingCompany\CertifactionClient\Contracts\FileTransaction as FileTransactionContract;
use AmaizingCompany\CertifactionClient\Contracts\IdentityTransaction as IdentityTransactionContract;
use AmaizingCompany\CertifactionClient\Contracts\SignatureTransaction as SignatureTransactionContract;
use AmaizingCompany\CertifactionClient\Events\UserInvitedToCertifaction;
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
        $this->app->bind(AccountContract::class, fn ($app) => $app->make(Account::class));
        $this->app->bind(DocumentContract::class, fn ($app) => $app->make(Document::class));
        $this->app->bind(FileTransactionContract::class, fn ($app) => $app->make(FileTransaction::class));
        $this->app->bind(IdentityTransactionContract::class, fn ($app) => $app->make(IdentityTransaction::class));
        $this->app->bind(SignatureTransactionContract::class, fn ($app) => $app->make(SignatureTransaction::class));
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
            return app(SignatureTransactionContract::class)::findOrFail(Crypt::decrypt($value));
        });
    }
}
