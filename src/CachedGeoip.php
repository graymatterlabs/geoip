<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp;

use Psr\SimpleCache\CacheInterface;

final class CachedGeoip
{
    public function __construct(private GeoIp $geoIp, private CacheInterface $cache)
    {
    }

    /**
     * Attempt to locate and remember the geolocation of the Ip address.
     *
     * @param string $ip
     *
     * @return \GrayMatterLabs\GeoIp\Location
     * @throws \GrayMatterLabs\GeoIp\Exceptions\InvalidIpAddressException
     * @throws \GrayMatterLabs\GeoIp\Exceptions\LocationNotFoundException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function locate(string $ip): Location
    {
        $key = $this->getCacheKey($ip);
        if ($cached = $this->cache->get($key)) {
            return $cached;
        }

        $location = $this->geoIp->locate($ip);

        if (! $location->isDefault) {
            $this->cache->set($key, $location);
        }

        return $location;
    }

    /**
     * Get the cache key for the Ip.
     *
     * @param string $ip
     *
     * @return string
     */
    private function getCacheKey(string $ip): string
    {
        return 'geoip:' . md5($ip);
    }
}
