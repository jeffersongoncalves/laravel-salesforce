<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Salesforce\Facades\Salesforce;

it('runs a SOSL search', function () {
    Http::fake([
        '*/services/oauth2/token' => Http::response(['access_token' => 'tok', 'instance_url' => 'https://example.my.salesforce.com']),
        '*/services/data/v59.0/search*' => Http::response(['searchRecords' => [['Id' => '003', 'Name' => 'Jane Doe']]]),
    ]);

    $result = Salesforce::search('FIND {Jane} IN ALL FIELDS RETURNING Contact(Id, Name)');

    expect($result['searchRecords'][0]['Name'])->toBe('Jane Doe');

    Http::assertSent(function ($request) {
        return str_contains((string) $request->url(), '/services/data/v59.0/search');
    });
});
