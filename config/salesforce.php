<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Salesforce Login URL
    |--------------------------------------------------------------------------
    |
    | Use https://test.salesforce.com for sandbox orgs.
    |
    */
    'login_url' => env('SALESFORCE_LOGIN_URL', 'https://login.salesforce.com'),

    /*
    |--------------------------------------------------------------------------
    | Connected App Credentials
    |--------------------------------------------------------------------------
    |
    | Find them under Setup > App Manager > your Connected App > View.
    |
    */
    'client_id' => env('SALESFORCE_CLIENT_ID', ''),
    'client_secret' => env('SALESFORCE_CLIENT_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | OAuth2 Password Grant Credentials
    |--------------------------------------------------------------------------
    |
    | The security token is appended to the password automatically. Reset it
    | under Settings > My Personal Information > Reset My Security Token.
    |
    */
    'username' => env('SALESFORCE_USERNAME', ''),
    'password' => env('SALESFORCE_PASSWORD', ''),
    'security_token' => env('SALESFORCE_SECURITY_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | REST API Version
    |--------------------------------------------------------------------------
    */
    'api_version' => env('SALESFORCE_API_VERSION', 'v59.0'),

];
