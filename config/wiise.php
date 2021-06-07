<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'provider'         => env('WIISE_PROVIDER', 'wiise'),
    'tennantId'         => env('WIISE_TENNANT_ID', ''),
    'appId'             => env('WIISE_APP_ID', ''),
    'appSecret'         => env('WIISE_APP_SECRET', ''),
    'redirectUri'       => env('WIISE_REDIRECT_URI', ''),
    'authority'         => env('WIISE_AUTHORITY', 'https://login.microsoftonline.com/'),
    'authoriseEndpoint' => env('WIISE_AUTHORISE_ENDPOINT', '/oauth2/authorize'),
    'tokenEndpoint'     => env('WIISE_TOKEN_ENDPOINT', '/oauth2/token'),
    'resource'          => env('WIISE_RESOURCE', 'https://api.businesscentral.dynamics.com'),
    'test' => 'Hello Test',
];