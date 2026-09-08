<?php

namespace JeffersonGoncalves\Salesforce\Tests;

use JeffersonGoncalves\Salesforce\SalesforceServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SalesforceServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('salesforce.login_url', 'https://login.salesforce.com');
        $app['config']->set('salesforce.client_id', 'test-client-id');
        $app['config']->set('salesforce.client_secret', 'test-client-secret');
        $app['config']->set('salesforce.username', 'test@example.com');
        $app['config']->set('salesforce.password', 'test-password');
        $app['config']->set('salesforce.security_token', 'test-token');
        $app['config']->set('salesforce.api_version', 'v59.0');
    }
}
