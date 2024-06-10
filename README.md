# Laravel Smaeday API Wrapper

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mchervenkov/sameday.svg?style=flat-square)](https://packagist.org/packages/mchervenkov/sameday)
[![Total Downloads](https://img.shields.io/packagist/dt/mchervenkov/sameday.svg?style=flat-square)](https://packagist.org/packages/mchervenkov/sameday)

[Sameday JSON API Documentation](https://sameday.bg/)

## Installation

You can install the package via composer:

```bash
composer require mchervenkov/econt
```

If you plan to use database for storing nomenclatures:

```bash
php artisan migrate
```

If you need to export configuration file:

```bash
php artisan vendor:publish --tag=sameday-config
```

If you need to export migrations:

```bash
php artisan vendor:publish --tag=sameday-migrations
```

If you need to export models:

```bash
php artisan vendor:publish --tag=sameday-models
```

If you need to export commands:

```bash
php artisan vendor:publish --tag=sameday-commands
```

## Configuration

```bash
SAMEDAY_ENV=test|production #default=test
SAMEDAY_AUTH_USERNAME= #default=test-sameday-username
SAMEDAY_AUTH_PASSWORD= #default=test-sameday-password
SAMEDAY_API_TEST_BASE_URI= #default=https://sameday-api-bg.demo.zitec.com
SAMEDAY_API_PRODUCTION_BASE_URI= #default=https://sameday-api.demo.zitec.com
SAMEDAY_API_TIMEOUT= #default=5
```

## Usage

Methods
```php

//Nomenclatures
$sameday = new Sameday();

// Client
$sameday->getServices();
$sameday->getPickupPoints();
$sameday->getAwbStatus();
$sameday->syncStatus();

// Geolocation
$sameday->getCounties();
$sameday->getCities();

// Lockers
$sameday->getLockers();
```

Commands

```bash
#get counties with database (use -h to view options)
php artisan sameday:get-counties

#get cities with database (use -h to view options)
php artisan sameday:get-cities

#create cities map with other carriers in database  (use -h to view options)
php artisan sameday:map-cities

#get lockers with database (use -h to view options)
php artisan sameday:get-lockers 

#get sameday api status (use -h to view options)
php artisan sameday:api-status
```

Models
```php
SamedayCity
SamedayCounty
SamedayApiStatus
SamedayLocker
CarrierCityMap
```

## Examples

Without Params
```php
$sameday = new Sameday();
$response = $sameday->getCities();
dd($response);

```

With Hydrator
```php
$sameday = new Sameday();
$cityHydrator = new City['countyCode' => 'BG'];
$response = $sameday->getCities($cityHydrator);
dd($response);
```

With Hydrator and Paginator
```php
$sameday = new Sameday();
$cityHydrator = new City['countyCode' => 'BG'];
$paginator = new Paginator(1, 50);
$response = $sameday->getCities($cityHydrator, $paginator);
dd($response);
```

### Testing
Before running tests set your api credentials in sameday.php config file
```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mario.chervenkov@gmail.com instead of using the issue tracker.

## Credits

-   [Mario Chervenkov](https://github.com/mariochervenkov)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
