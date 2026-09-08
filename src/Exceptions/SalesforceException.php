<?php

namespace JeffersonGoncalves\Salesforce\Exceptions;

use Illuminate\Http\Client\Response;
use RuntimeException;

class SalesforceException extends RuntimeException
{
    /** @var array<int|string, mixed> */
    protected array $errorBody = [];

    public static function fromResponse(Response $response): self
    {
        $body = $response->json();
        $body = is_array($body) ? $body : [];

        $message = $body[0]['message']
            ?? $body['error_description']
            ?? $body['error']
            ?? "Salesforce API error (HTTP {$response->status()}).";

        $exception = new self((string) $message, $response->status());
        $exception->errorBody = $body;

        return $exception;
    }

    /** @return array<int|string, mixed> */
    public function errorBody(): array
    {
        return $this->errorBody;
    }
}
