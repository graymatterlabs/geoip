<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp\Contracts;

use GrayMatterLabs\GeoIp\Location;

interface Service
{
    /**
     * Attempt to locate the geolocation of the Ip address.
     *
     *
     * @throws \GrayMatterLabs\GeoIp\Exceptions\LocationNotFoundException
     */
    public function locate(string $ip): Location;
}
