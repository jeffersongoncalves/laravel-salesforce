<?php

namespace Jeffersongoncalves\Salesforce\Tests;

use Jeffersongoncalves\Salesforce\SalesforceServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SalesforceServiceProvider::class,
        ];
    }
}
