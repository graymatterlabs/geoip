# GeoIp

[![Latest Version on Packagist](https://img.shields.io/packagist/v/graymatterlabs/geoip.svg?style=flat-square)](https://packagist.org/packages/graymatterlabs/geoip)
[![Tests](https://github.com/graymatterlabs/geoip/actions/workflows/run-tests.yml/badge.svg?branch=master)](https://github.com/graymatterlabs/geoip/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/graymatterlabs/geoip.svg?style=flat-square)](https://packagist.org/packages/graymatterlabs/geoip)

This package ships with a set of runtime implementations of `psr/simple-cache` via `graymatterlabs/simple-cache`. You're encouraged to provide your own implementation of PSR-16 using a persistent storage solution like Redis or Memcache in order to cache your locations after their first lookup.

You can choose between instances of `GrayMatterLabs\GeoIp\ImmutableLocation` over `GrayMatterLabs\GeoIp\Location` by calling `GrayMatterLabs\GeoIp\GeoIp::setImmutable` with a value of `true`. The Immutable instance is fundamentally the same except for that it is unable to be modified, with optional strictness which will raise an exception if an attempt is made to modify it. You can toggle this strictness by calling `GrayMatterLabs\GeoIp\ImmutableLocation::setStrict`.

## Installation

You can install the package via composer:

```bash
composer require graymatterlabs/geoip:^0.1
```

## Usage

```php
use GrayMatterLabs\GeoIp\GeoIp;
use GrayMatterLabs\SimpleCache\NullCache;

$geoip = new GeoIp($locator, new NullCache());

$location = $geoip->locate($ip);

// for immutable locations
GeoIp::setImmutable(true);
$location = $geoip->locate($ip);
$location->country = 'US'; // nothing changes

ImmutableLocation::setStrict(true);
$location = $geoip->locate($ip);
$location->country = 'US'; // an ImmutabilityException is thrown
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
