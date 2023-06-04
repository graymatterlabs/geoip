# GeoIp

[![Latest Version on Packagist](https://img.shields.io/packagist/v/graymatterlabs/geoip.svg?style=flat-square)](https://packagist.org/packages/graymatterlabs/geoip)
[![Tests](https://github.com/graymatterlabs/geoip/actions/workflows/run-tests.yml/badge.svg?branch=master)](https://github.com/graymatterlabs/geoip/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/graymatterlabs/geoip.svg?style=flat-square)](https://packagist.org/packages/graymatterlabs/geoip)

This package provides an opinionated framework for resolving geolocations from IP addresses.

## Installation

You can install the package via composer:

```bash
composer require graymatterlabs/geoip:^2.0
```

## Usage

```php
use GrayMatterLabs\GeoIp\GeoIp;

$geoip = new GeoIp($locator);
// $geoip = new CachedGeoIp($geoip, $cache);

$location = $geoip->locate($ip);

$location->countryCode; // 'US'

if ($location->isDefault) {
    //
}
```

## Testing

```bash
composer test
```

## Changelog

Please see the [Release Notes](../../releases) for more information on what has changed recently.

## Credits

- [Ryan Colson](https://github.com/ryancco)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
