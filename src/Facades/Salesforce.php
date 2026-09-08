<?php

namespace JeffersonGoncalves\Salesforce\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JeffersonGoncalves\Salesforce\Salesforce
 */
class Salesforce extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JeffersonGoncalves\Salesforce\Salesforce::class;
    }
}
