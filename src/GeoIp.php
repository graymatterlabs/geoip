<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp;

use Closure;
use GrayMatterLabs\GeoIp\Contracts\Locator;
use GrayMatterLabs\GeoIp\Exceptions\InvalidIpAddressException;
use GrayMatterLabs\GeoIp\Exceptions\LocationNotFoundException;
use Psr\SimpleCache\CacheInterface;

class GeoIp
{
    protected static ?Location $default;

    public function __construct(protected Locator $locator, protected CacheInterface $cache)
    {
    }

    /**
     * Attempt to locate the geolocation of the Ip address.
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
        if (! $this->isValid($ip)) {
            throw new InvalidIpAddressException($ip);
        }

        return $this->remember($ip, function ($ip) {
            try {
                if ($this->isPrivateRange($ip)) {
                    throw new InvalidIpAddressException($ip);
                }

                return $this->locator->locate($ip);
            } catch (LocationNotFoundException | InvalidIpAddressException $e) {
                if (! $this->hasDefaultLocation()) {
                    throw $e;
                }

                return $this->getDefaultLocation($ip);
            }
        });
    }

    /**
     * Resolve and remember the geolocation of the Ip address.
     *
     * @param string $ip
     * @param \Closure $closure
     *
     * @return \GrayMatterLabs\GeoIp\Location
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function remember(string $ip, Closure $closure): Location
    {
        $key = $this->getCacheKey($ip);
        if ($cached = $this->cache->get($key)) {
            return $cached;
        }

        /** @var \GrayMatterLabs\GeoIp\Location $location */
        $location = $closure($ip);

        if (! $location->isDefault) {
            $this->cache->set($key, $location);
        }

        return $location;
    }

    /**
     * Determine whether the Ip address is valid.
     *
     * @param string $ip
     *
     * @return bool
     */
    protected function isValid(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }

    /**
     * Determine whether Ip address is in a private range.
     *
     * @param string $ip
     *
     * @return bool
     */
    protected function isPrivateRange(string $ip): bool
    {
        return ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
        && ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE);
    }

    /**
     * Get the cache key for the Ip.
     *
     * @param string $ip
     *
     * @return string
     */
    protected function getCacheKey(string $ip): string
    {
        return 'geoip:'.$ip;
    }

    /**
     * Get the location to use when an Ip addresses geolocation cannot be found.
     *
     * @param string $ip
     *
     * @return \GrayMatterLabs\GeoIp\Location
     */
    protected function getDefaultLocation(string $ip): Location
    {
        return static::$default->clone(ip: $ip, isDefault: true);
    }

    /**
     * Determine whether a default location has been specified.
     *
     * @return bool
     */
    protected function hasDefaultLocation(): bool
    {
        return isset(static::$default);
    }

    /**
     * Specify the location to use when an Ip address geolocation cannot be found.
     *
     * @param \GrayMatterLabs\GeoIp\Location|null $location
     *
     * @return void
     */
    public static function setDefaultLocation(?Location $location): void
    {
        static::$default = $location;
    }
}
