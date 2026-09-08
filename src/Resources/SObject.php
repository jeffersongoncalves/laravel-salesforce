<?php

namespace JeffersonGoncalves\Salesforce\Resources;

use JeffersonGoncalves\Salesforce\SalesforceClient;

class SObject
{
    public function __construct(
        protected SalesforceClient $client,
        protected string $name,
    ) {}

    /** @param string[] $fields */
    public function find(string $id, array $fields = []): array
    {
        $query = $fields === [] ? [] : ['fields' => implode(',', $fields)];

        return $this->client->get("/sobjects/{$this->name}/{$id}", $query);
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): array
    {
        return $this->client->post("/sobjects/{$this->name}", $data);
    }

    /** @param array<string, mixed> $data */
    public function update(string $id, array $data): void
    {
        $this->client->patch("/sobjects/{$this->name}/{$id}", $data);
    }

    public function delete(string $id): void
    {
        $this->client->delete("/sobjects/{$this->name}/{$id}");
    }

    public function describe(): array
    {
        return $this->client->get("/sobjects/{$this->name}/describe");
    }
}
