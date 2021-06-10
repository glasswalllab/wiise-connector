# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/glasswalllab/wiise-connector.svg?style=flat-square)](https://packagist.org/packages/glasswalllab/wiise-connector)
[![Total Downloads](https://img.shields.io/packagist/dt/glasswalllab/wiise-connector.svg?style=flat-square)](https://packagist.org/packages/glasswalllab/wiise-connector)
![GitHub Actions](https://github.com/glasswalllab/wiise-connector/actions/workflows/main.yml/badge.svg)

Provides a connection to Wiise (Microsoft Business Central) for Laravel applications.

## Installation

You can install the package via composer:

```bash
composer require glasswalllab/wiise-connector
```

## Usage

```php
Setup App in Microsoft Azure

Need to update .env with the following

WIISE_COMPANY_NAME=YOUR_COMAPNY_NAME
WIISE_TENANT_ID=YOUR_TENANT_ID
WIISE_APP_ID=YOUR_APP_ID
WIISE_APP_SECRET=YOUR_APP_SECRET
WIISE_REDIRECT_URI=YOUR_REDIRECT_URKL

WIISE_PROVIDER=wiise
WIISE_SCOPES='Financials.ReadWrite.All offline_access'
WIISE_AUTHORITY=https://login.microsoftonline.com/
WIISE_AUTHORISE_ENDPOINT=/oauth2/authorize?resource=https://api.businesscentral.dynamics.com
WIISE_TOKEN_ENDPOINT=/oauth2/token?resource=https://api.businesscentral.dynamics.com
WIISE_RESOURCE=https://api.businesscentral.dynamics.com
WIISE_BASE_API_URL=https://wiise.api.bc.dynamics.com/v2.0/

Run php artisan migrate to load the api_token table

```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email sreid@gwlab.com.au instead of using the issue tracker.