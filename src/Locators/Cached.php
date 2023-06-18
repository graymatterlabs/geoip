<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp\Locators;

use GrayMatterLabs\GeoIp\Contracts\Locator;
use GrayMatterLabs\GeoIp\Location;
use Psr\SimpleCache\CacheInterface;

/**
 * A decorator to add caching to any Locator implementation.
 */
final class Cached implements Locator
{
    private string $prefix;

    public function __construct(private Locator $geoIp, private CacheInterface $cache, string $prefix = 'geoip')
    {
        $this->prefix = $this->normalizePrefix($prefix);
    }

    /**
     * Attempt to remember or locate the geolocation of the Ip address.
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

        if (! $location->isDefault()) {
            $this->cache->set($key, $location);
        }

        return $location;
    }

    /**
     * Normalize the cache key prefix.
     *
     * @param string $prefix
     * @return string
     */
    private function normalizePrefix(string $prefix): string
    {
        return preg_replace("/[^A-Za-z0-9._]/", '', $prefix);
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
        return $this->prefix . ':' . md5($ip);
    }
}
