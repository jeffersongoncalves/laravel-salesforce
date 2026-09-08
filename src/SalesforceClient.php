<?php

namespace JeffersonGoncalves\Salesforce;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Salesforce\Exceptions\SalesforceException;

/**
 * Thin wrapper around Laravel's Http client for the Salesforce REST API.
 *
 * Authenticates via the OAuth2 username-password flow and caches the access
 * token, re-authenticating once on a 401 before giving up.
 *
 * ponytail: password grant only, no JWT bearer / refresh-token flow — add if
 * a use case needs it (e.g. no static password on file).
 */
class SalesforceClient
{
    protected const CACHE_KEY = 'salesforce.access_token';

    protected ?string $accessToken = null;

    protected ?string $instanceUrl = null;

    public function __construct(
        protected string $loginUrl,
        protected string $clientId,
        protected string $clientSecret,
        protected string $username,
        protected string $password,
        protected string $securityToken,
        protected string $apiVersion,
    ) {}

    /** @param array<string, mixed> $query */
    public function get(string $path, array $query = []): array
    {
        return $this->request('get', $path, ['query' => $query]);
    }

    /** @param array<string, mixed> $data */
    public function post(string $path, array $data = []): array
    {
        return $this->request('post', $path, ['json' => $data]);
    }

    /** @param array<string, mixed> $data */
    public function patch(string $path, array $data = []): array
    {
        return $this->request('patch', $path, ['json' => $data]);
    }

    public function delete(string $path): array
    {
        return $this->request('delete', $path);
    }

    /** @param array<string, mixed> $options */
    protected function request(string $method, string $path, array $options = [], bool $retry = true): array
    {
        $this->authenticate();

        $response = Http::withToken((string) $this->accessToken)
            ->acceptJson()
            ->baseUrl((string) $this->instanceUrl)
            ->send(strtoupper($method), "/services/data/{$this->apiVersion}{$path}", $options);

        if ($response->status() === 401 && $retry) {
            Cache::forget(self::CACHE_KEY);
            $this->accessToken = null;

            return $this->request($method, $path, $options, retry: false);
        }

        if ($response->failed()) {
            throw SalesforceException::fromResponse($response);
        }

        return (array) ($response->json() ?? []);
    }

    protected function authenticate(): void
    {
        if ($this->accessToken !== null) {
            return;
        }

        /** @var array{access_token: string, instance_url: string}|null $cached */
        $cached = Cache::get(self::CACHE_KEY);

        if ($cached !== null) {
            $this->accessToken = $cached['access_token'];
            $this->instanceUrl = $cached['instance_url'];

            return;
        }

        $response = Http::asForm()->post("{$this->loginUrl}/services/oauth2/token", [
            'grant_type' => 'password',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'username' => $this->username,
            'password' => $this->password.$this->securityToken,
        ]);

        if ($response->failed()) {
            throw SalesforceException::fromResponse($response);
        }

        $data = (array) $response->json();

        $this->accessToken = (string) $data['access_token'];
        $this->instanceUrl = (string) $data['instance_url'];

        Cache::put(self::CACHE_KEY, [
            'access_token' => $this->accessToken,
            'instance_url' => $this->instanceUrl,
        ], now()->addHours(2));
    }
}
