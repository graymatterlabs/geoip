<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp\Tests\Unit;

use GrayMatterLabs\GeoIp\Exceptions\InvalidIpAddressException;
use GrayMatterLabs\GeoIp\GeoIp;
use GrayMatterLabs\GeoIp\Location;
use GrayMatterLabs\GeoIp\Tests\Mocks\MockLocator;
use PHPUnit\Framework\TestCase;

class GeoIpTest extends TestCase
{
    /** @dataProvider providesValidIpAddresses */
    public function test_it_locates_an_ip_addresses_geolocation(string $ip): void
    {
        $location = $this->makeGeoIp()->locate($ip);

        $this->assertFalse($location->isDefault);
        $this->assertEquals($ip, $location->ip);
    }

    /** @dataProvider providesInvalidIpAddresses */
    public function test_it_requires_a_valid_ip_address(string $ip): void
    {
        $this->expectException(InvalidIpAddressException::class);
        $this->makeGeoIp()->locate($ip);
    }

    /** @dataProvider providesPrivateIpAddresses */
    public function test_it_falls_back_to_a_default_location_for_reserved_ips(string $ip): void
    {
        GeoIp::setDefaultLocation(new Location('1.1.1.1'));

        $location = $this->makeGeoIp()->locate($ip);
        $this->assertTrue($location->isDefault);
        GeoIp::setDefaultLocation(null);
    }

    public function test_it_raises_an_exception_if_a_default_location_is_not_specified_and_an_ip_address_cannot_be_located(): void
    {
        $this->expectException(InvalidIpAddressException::class);
        $this->makeGeoIp()->locate('127.0.0.1');
    }

    public function providesValidIpAddresses(): array
    {
        return [
            'ipv4' => ['1.1.1.1'],
            'ipv6' => ['2606:4700:4700::1111'],
        ];
    }

    public function providesInvalidIpAddresses(): array
    {
        return [
            'ipv4' => ['1.1.1.1111'],
            'ipv6' => ['fe80:2030:31:24'],
        ];
    }

    public function providesPrivateIpAddresses(): array
    {
        return [
            'ipv4' => ['127.0.0.1'],
            'ipv6' => ['FDC8:BF8B:E62C:ABCD:1111:2222:3333:4444'],
        ];
    }

    public function makeGeoIp(): GeoIp
    {
        return new GeoIp(new MockLocator());
    }
}
