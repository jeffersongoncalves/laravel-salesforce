<div class="filament-hidden">

![Laravel Salesforce](https://raw.githubusercontent.com/jeffersongoncalves/laravel-salesforce/main/art/jeffersongoncalves-laravel-salesforce.png)

</div>

# Laravel Salesforce

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-salesforce.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-salesforce)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-salesforce/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-salesforce/actions?query=workflow%3ATests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-salesforce/pint.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-salesforce/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-salesforce.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-salesforce)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-salesforce.svg?style=flat-square)](LICENSE.md)

A PHP/Laravel client for the [Salesforce](https://www.salesforce.com/) REST API. Run SOQL queries, SOSL searches, and CRUD any sobject, authenticated via the OAuth2 username-password flow with automatic token caching and re-authentication.

## Features

- SOQL queries (`/query`)
- SOSL search (`/search`)
- Sobject CRUD: find, create, update, delete (`/sobjects/{name}`)
- Sobject describe (`/sobjects/{name}/describe`)
- OAuth2 password grant with cached access token, transparent re-auth on a 401
- Throws `SalesforceException` (with the original API error body) on any non-2xx response

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-salesforce
```

Publish the config file:

```bash
php artisan vendor:publish --tag=salesforce-config
```

Set your Salesforce connected-app and user credentials in `.env`:

```env
SALESFORCE_LOGIN_URL=https://login.salesforce.com
SALESFORCE_CLIENT_ID=your-connected-app-client-id
SALESFORCE_CLIENT_SECRET=your-connected-app-client-secret
SALESFORCE_USERNAME=your-username
SALESFORCE_PASSWORD=your-password
SALESFORCE_SECURITY_TOKEN=your-security-token
SALESFORCE_API_VERSION=v59.0
```

Use `https://test.salesforce.com` for sandbox orgs. Reset the security token under **Settings > My Personal Information > Reset My Security Token**.

## Configuration

```php
// config/salesforce.php
return [
    'login_url' => env('SALESFORCE_LOGIN_URL', 'https://login.salesforce.com'),
    'client_id' => env('SALESFORCE_CLIENT_ID', ''),
    'client_secret' => env('SALESFORCE_CLIENT_SECRET', ''),
    'username' => env('SALESFORCE_USERNAME', ''),
    'password' => env('SALESFORCE_PASSWORD', ''),
    'security_token' => env('SALESFORCE_SECURITY_TOKEN', ''),
    'api_version' => env('SALESFORCE_API_VERSION', 'v59.0'),
];
```

## Usage

The package is resolved via the `Salesforce` facade or by injecting `JeffersonGoncalves\Salesforce\Salesforce`.

### Query (SOQL)

```php
use JeffersonGoncalves\Salesforce\Facades\Salesforce;

$result = Salesforce::query('SELECT Id, Name FROM Account LIMIT 10');
// $result['records']
```

### Search (SOSL)

```php
$result = Salesforce::search('FIND {Jane} IN ALL FIELDS RETURNING Contact(Id, Name)');
// $result['searchRecords']
```

### Find a record

```php
$account = Salesforce::sobject('Account')->find('001XX000003DHPh', ['Id', 'Name', 'Industry']);
```

### Create a record

```php
$result = Salesforce::sobject('Contact')->create([
    'FirstName' => 'Jane',
    'LastName' => 'Doe',
    'Email' => 'jane@example.com',
]);
// $result['id']
```

### Update a record

```php
Salesforce::sobject('Contact')->update('003XX0000012345', [
    'Title' => 'Senior Developer',
]);
```

### Delete a record

```php
Salesforce::sobject('Contact')->delete('003XX0000012345');
```

### Describe an sobject

```php
$fields = Salesforce::sobject('Contact')->describe();
```

### Error handling

Any non-2xx API response throws `JeffersonGoncalves\Salesforce\Exceptions\SalesforceException`, which exposes the decoded error body:

```php
use JeffersonGoncalves\Salesforce\Exceptions\SalesforceException;

try {
    Salesforce::sobject('Account')->find('unknown-id');
} catch (SalesforceException $e) {
    logger()->error($e->getMessage(), $e->errorBody());
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email the author instead of using the issue tracker.

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
