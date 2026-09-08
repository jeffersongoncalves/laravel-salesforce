<?php

namespace Jeffersongoncalves\Salesforce;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SalesforceServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-salesforce')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations();
    }
}
