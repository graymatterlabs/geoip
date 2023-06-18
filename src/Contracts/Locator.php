<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp\Contracts;

use GrayMatterLabs\GeoIp\Location;

interface Locator
{
    /**
     * Attempt to locate the geolocation of the Ip address.
     *
     * @param string $ip
     *
     * @return \GrayMatterLabs\GeoIp\Location
     * @throws \GrayMatterLabs\GeoIp\Exceptions\LocationNotFoundException
     */
    public function locate(string $ip): Location;
}
