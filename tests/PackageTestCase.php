<?php

namespace AmaizingCompany\CertifactionClient\Tests;

class PackageTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $migrations = [
            'create_certifaction_accounts_table',
            'create_certifaction_identity_transactions_table',
            'create_certifaction_documents_table',
            'create_certifaction_signature_transactions_table',
            'create_certifaction_signature_transactions_documents_table',
            'create_certifaction_file_transactions_table',
            'add_original_transaction_id_to_certifaction_file_transactions_table',
        ];

        $testMigrations = [
            'create_users_table',
            'create_files_table',
        ];

        foreach ($migrations as $migration) {
            $migration = include __DIR__.'/../database/migrations/'.$migration.'.php.stub';
            $migration->up();
        }

        foreach ($testMigrations as $migration) {
            $migration = include __DIR__.'/migrations/'.$migration.'.php';
            $migration->up();
        }
    }
}
