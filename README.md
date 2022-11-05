# GeoIp

[![Latest Version on Packagist](https://img.shields.io/packagist/v/graymatterlabs/geoip.svg?style=flat-square)](https://packagist.org/packages/graymatterlabs/geoip)
[![Tests](https://github.com/graymatterlabs/geoip/actions/workflows/run-tests.yml/badge.svg?branch=master)](https://github.com/graymatterlabs/geoip/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/graymatterlabs/geoip.svg?style=flat-square)](https://packagist.org/packages/graymatterlabs/geoip)

This package provides an opinionated framework for resolving geolocations from IP addresses.

This package ships with a set of runtime implementations of `psr/simple-cache` via `graymatterlabs/simple-cache`. You're encouraged to provide your own implementation of PSR-16 using a persistent storage solution like Redis or Memcache in order to cache your locations after their first lookup.

## Installation

You can install the package via composer:

```bash
composer require graymatterlabs/geoip:^0.3
```

## Usage

```php
use GrayMatterLabs\GeoIp\GeoIp;
use GrayMatterLabs\SimpleCache\NullCache;

$geoip = new GeoIp($locator, new NullCache());

$location = $geoip->locate($ip);

$location->countryCode; // 'US'
```
For examples of usage and implementation, please check out the `tests/` directory.

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
