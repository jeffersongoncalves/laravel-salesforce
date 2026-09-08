<?php

use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\Response as HttpResponse;
use JeffersonGoncalves\Salesforce\Exceptions\SalesforceException;

function fakeSalesforceResponse(int $status, ?array $body): Response
{
    $psr = new GuzzleHttp\Psr7\Response($status, [], json_encode($body));

    return new HttpResponse($psr);
}

it('builds the exception message from a REST API error list', function () {
    $response = fakeSalesforceResponse(400, [['message' => 'MALFORMED_QUERY: unexpected token', 'errorCode' => 'MALFORMED_QUERY']]);

    $exception = SalesforceException::fromResponse($response);

    expect($exception->getMessage())->toBe('MALFORMED_QUERY: unexpected token')
        ->and($exception->getCode())->toBe(400)
        ->and($exception->errorBody())->toBe([['message' => 'MALFORMED_QUERY: unexpected token', 'errorCode' => 'MALFORMED_QUERY']]);
});

it('builds the exception message from an OAuth2 error response', function () {
    $response = fakeSalesforceResponse(400, ['error' => 'invalid_grant', 'error_description' => 'authentication failure']);

    $exception = SalesforceException::fromResponse($response);

    expect($exception->getMessage())->toBe('authentication failure');
});

it('falls back to a generic message when the body has no known error keys', function () {
    $response = fakeSalesforceResponse(500, []);

    $exception = SalesforceException::fromResponse($response);

    expect($exception->getMessage())->toBe('Salesforce API error (HTTP 500).');
});
