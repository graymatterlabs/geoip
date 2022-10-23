<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp\Tests\Mocks;

use GrayMatterLabs\GeoIp\Contracts\Locator;
use GrayMatterLabs\GeoIp\Location;

class MockLocator implements Locator
{
    public function locate(string $ip): Location
    {
        return new Location($ip);
    }
}
