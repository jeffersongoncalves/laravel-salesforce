<?php

namespace JeffersonGoncalves\Salesforce;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SalesforceServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('salesforce')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(SalesforceClient::class, function () {
            return new SalesforceClient(
                loginUrl: (string) config('salesforce.login_url'),
                clientId: (string) config('salesforce.client_id'),
                clientSecret: (string) config('salesforce.client_secret'),
                username: (string) config('salesforce.username'),
                password: (string) config('salesforce.password'),
                securityToken: (string) config('salesforce.security_token'),
                apiVersion: (string) config('salesforce.api_version'),
            );
        });

        $this->app->singleton(Salesforce::class, function ($app) {
            return new Salesforce($app->make(SalesforceClient::class));
        });
    }
}
