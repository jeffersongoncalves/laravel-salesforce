<?php

namespace Jeffersongoncalves\Salesforce\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jeffersongoncalves\Salesforce\Salesforce
 */
class Salesforce extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-salesforce';
    }
}
