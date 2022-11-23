<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp\Services;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\ProviderInterface;
use GeoIp2\WebService\Client;
use GrayMatterLabs\GeoIp\Contracts\Locator;
use GrayMatterLabs\GeoIp\Exceptions\LocationNotFoundException;
use GrayMatterLabs\GeoIp\Location;
use GrayMatterLabs\GeoIp\Support\Currency;

/*
 * This service is compatible with both free and paid GeoIP2 databases and web services.
 *
 * Using this service requires geoip2/geoip2:~2.1.
 *
 * To learn more including where to license the databases and/or services from, read here:
 * https://www.maxmind.com
 */
class MaxMind implements Locator
{
    public function __construct(private ProviderInterface $provider)
    {
    }

    public static function database(string $path, array $locales = ['en']): static
    {
        return new static(new Reader($path, $locales));
    }

    public static function web(int $accountId, string $licenseKey, array $locales = ['en']): static
    {
        return new static(new Client($accountId, $licenseKey, $locales));
    }

    public function locate(string $ip): Location
    {
        try {
            $location = $this->provider->city($ip);
        } catch (AddressNotFoundException $e) {
            throw new LocationNotFoundException($ip);
        }

        return new Location(
            ip: $ip,
            countryCode: $location->country->isoCode,
            countryName: $location->country->name,
            stateCode: $location->mostSpecificSubdivision->isoCode,
            stateName: $location->mostSpecificSubdivision->name,
            city: $location->city->name,
            postalCode: $location->postal->code,
            continent: $location->continent->code,
            latitude: $location->location->latitude,
            longitude: $location->location->longitude,
            timezone: $location->location->timeZone,
            currency: Currency::fromCountryCode($location->country->isoCode ?? '')
        );
    }
}
