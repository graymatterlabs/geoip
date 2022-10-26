<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp\Services;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use GrayMatterLabs\GeoIp\Contracts\Locator;
use GrayMatterLabs\GeoIp\Exceptions\LocationNotFoundException;
use GrayMatterLabs\GeoIp\Location;

/*
 * This service is compatible with both GeoIP2 enterprise and GeoLite2 free databases.
 *
 * Using this service requires geoip2/geoip2:~2.1.
 *
 * To learn more including where to purchase and/or download the databases, read here:
 * https://www.maxmind.com
 */
class MaxMindDatabase implements Locator
{
    private Reader $reader;

    public function __construct(string $path, array $locales = ['en'])
    {
        $this->reader = new Reader($path, $locales);
    }

    public function locate(string $ip): Location
    {
        try {
            $location = $this->reader->city($ip);
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
            latitude: $location->location->latitude,
            longitude: $location->location->longitude
        );
    }
}
