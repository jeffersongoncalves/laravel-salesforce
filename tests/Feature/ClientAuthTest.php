<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Salesforce\Facades\Salesforce;

it('caches the access token across requests', function () {
    Http::fake([
        '*/services/oauth2/token' => Http::response(['access_token' => 'tok', 'instance_url' => 'https://example.my.salesforce.com']),
        '*/services/data/v59.0/query*' => Http::response(['records' => []]),
    ]);

    Salesforce::query('SELECT Id FROM Account');
    Salesforce::query('SELECT Id FROM Contact');

    Http::assertSentCount(3);
});

it('re-authenticates once on a 401 and retries the request', function () {
    Http::fakeSequence('*/services/oauth2/token')
        ->push(['access_token' => 'expired', 'instance_url' => 'https://example.my.salesforce.com'])
        ->push(['access_token' => 'fresh', 'instance_url' => 'https://example.my.salesforce.com']);

    Http::fakeSequence('*/services/data/v59.0/query*')
        ->push([['message' => 'Session expired or invalid', 'errorCode' => 'INVALID_SESSION_ID']], 401)
        ->push(['records' => []]);

    $result = Salesforce::query('SELECT Id FROM Account');

    expect($result['records'])->toBe([]);
});
