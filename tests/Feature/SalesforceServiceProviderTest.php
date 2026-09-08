<?php

use JeffersonGoncalves\Salesforce\Facades\Salesforce;
use JeffersonGoncalves\Salesforce\Salesforce as SalesforceManager;

it('merges the default config', function () {
    expect(config('salesforce.client_id'))->toBe('test-client-id');
});

it('resolves the facade to the manager singleton', function () {
    expect(Salesforce::getFacadeRoot())->toBeInstanceOf(SalesforceManager::class);
});

it('always resolves the same manager instance', function () {
    expect(app(SalesforceManager::class))->toBe(app(SalesforceManager::class));
});
