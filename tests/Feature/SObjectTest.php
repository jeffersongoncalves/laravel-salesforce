<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Salesforce\Facades\Salesforce;

beforeEach(function () {
    Http::fake([
        '*/services/oauth2/token' => Http::response(['access_token' => 'tok', 'instance_url' => 'https://example.my.salesforce.com']),
    ]);
});

it('finds a record by id', function () {
    Http::fake([
        '*/services/oauth2/token' => Http::response(['access_token' => 'tok', 'instance_url' => 'https://example.my.salesforce.com']),
        '*/services/data/v59.0/sobjects/Account/001*' => Http::response(['Id' => '001', 'Name' => 'Acme']),
    ]);

    $result = Salesforce::sobject('Account')->find('001', ['Id', 'Name']);

    expect($result['Name'])->toBe('Acme');

    Http::assertSent(function ($request) {
        return str_contains((string) $request->url(), '/sobjects/Account/001')
            && $request['fields'] === 'Id,Name';
    });
});

it('creates a record', function () {
    Http::fake([
        '*/services/oauth2/token' => Http::response(['access_token' => 'tok', 'instance_url' => 'https://example.my.salesforce.com']),
        '*/services/data/v59.0/sobjects/Account' => Http::response(['id' => '001', 'success' => true], 201),
    ]);

    $result = Salesforce::sobject('Account')->create(['Name' => 'Acme']);

    expect($result['id'])->toBe('001');
});

it('updates a record', function () {
    Http::fake([
        '*/services/oauth2/token' => Http::response(['access_token' => 'tok', 'instance_url' => 'https://example.my.salesforce.com']),
        '*/services/data/v59.0/sobjects/Account/001' => Http::response(null, 204),
    ]);

    Salesforce::sobject('Account')->update('001', ['Name' => 'Acme Inc.']);

    Http::assertSent(function ($request) {
        return $request->method() === 'PATCH' && $request['Name'] === 'Acme Inc.';
    });
});

it('deletes a record', function () {
    Http::fake([
        '*/services/oauth2/token' => Http::response(['access_token' => 'tok', 'instance_url' => 'https://example.my.salesforce.com']),
        '*/services/data/v59.0/sobjects/Account/001' => Http::response(null, 204),
    ]);

    Salesforce::sobject('Account')->delete('001');

    Http::assertSent(fn ($request) => $request->method() === 'DELETE');
});

it('describes an sobject', function () {
    Http::fake([
        '*/services/oauth2/token' => Http::response(['access_token' => 'tok', 'instance_url' => 'https://example.my.salesforce.com']),
        '*/services/data/v59.0/sobjects/Account/describe' => Http::response(['name' => 'Account', 'fields' => []]),
    ]);

    $result = Salesforce::sobject('Account')->describe();

    expect($result['name'])->toBe('Account');
});
