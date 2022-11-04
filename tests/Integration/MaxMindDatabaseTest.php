<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp\Tests\Integration;

use GeoIp2\Database\Reader;
use GrayMatterLabs\GeoIp\GeoIp;
use GrayMatterLabs\GeoIp\Services\MaxMind;
use GrayMatterLabs\SimpleCache\NullCache;
use PHPUnit\Framework\TestCase;

class MaxMindDatabaseTest extends TestCase
{
    public function test_it_locates_an_ip_addresses_geolocation(): void
    {
        $geoip = new GeoIp(
            new MaxMind(new Reader(__DIR__ . '/../Fixtures/GeoLite2-City.mmdb')),
            new NullCache()
        );

        $location = $geoip->locate('8.8.8.8');

        $this->assertEquals('8.8.8.8', $location->ip);
        $this->assertFalse($location->isDefault);
    }
}
