<?php

namespace JeffersonGoncalves\Salesforce;

use JeffersonGoncalves\Salesforce\Resources\SObject;

/**
 * Entry point for SOQL queries, SOSL search, and per-sobject CRUD.
 */
class Salesforce
{
    public function __construct(
        protected SalesforceClient $client,
    ) {}

    public function query(string $soql): array
    {
        return $this->client->get('/query', ['q' => $soql]);
    }

    public function search(string $sosl): array
    {
        return $this->client->get('/search', ['q' => $sosl]);
    }

    public function sobject(string $name): SObject
    {
        return new SObject($this->client, $name);
    }
}
