<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Salesforce\Exceptions\SalesforceException;
use JeffersonGoncalves\Salesforce\Facades\Salesforce;

it('runs a SOQL query', function () {
    Http::fake([
        '*/services/oauth2/token' => Http::response(['access_token' => 'tok', 'instance_url' => 'https://example.my.salesforce.com']),
        '*/services/data/v59.0/query*' => Http::response(['totalSize' => 1, 'done' => true, 'records' => [['Id' => '001', 'Name' => 'Acme']]]),
    ]);

    $result = Salesforce::query('SELECT Id, Name FROM Account');

    expect($result['records'][0]['Name'])->toBe('Acme');

    Http::assertSent(function ($request) {
        return str_contains((string) $request->url(), '/services/data/v59.0/query')
            && $request['q'] === 'SELECT Id, Name FROM Account';
    });
});

it('throws a SalesforceException on a failed query', function () {
    Http::fake([
        '*/services/oauth2/token' => Http::response(['access_token' => 'tok', 'instance_url' => 'https://example.my.salesforce.com']),
        '*/services/data/v59.0/query*' => Http::response([['message' => 'MALFORMED_QUERY: unexpected token', 'errorCode' => 'MALFORMED_QUERY']], 400),
    ]);

    Salesforce::query('SELECT');
})->throws(SalesforceException::class, 'MALFORMED_QUERY: unexpected token');
