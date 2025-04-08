<?php

namespace AmaizingCompany\CertifactionClient\Tests;

use AmaizingCompany\CertifactionClient\CertifactionClientServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'AmaizingCompany\\CertifactionClient\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            CertifactionClientServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app->useEnvironmentPath(__DIR__.'/..');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);
        parent::getEnvironmentSetUp($app);

        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        config()->set('app.key', 'base64:Zm54bWdrOWczNXFjM3U3dmlqcWxld2Z5YnBjMWZpZ3M=');

//        $migration = include __DIR__.'/migrations/create_users_table.php';
//        $migration->up();

    }

//    protected function defineDatabaseMigrations()
//    {
//        $this->loadMigrationsFrom([__DIR__.'/../database/migrations', __DIR__ ]);
//    }
}
