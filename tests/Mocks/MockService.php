<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp\Tests\Mocks;

use GrayMatterLabs\GeoIp\Contracts\Service;
use GrayMatterLabs\GeoIp\Location;

class MockService implements Service
{
    public function locate(string $ip): Location
    {
        return new Location($ip);
    }
}
