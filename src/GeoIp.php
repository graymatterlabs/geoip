<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp;

use GrayMatterLabs\GeoIp\Contracts\Locator;
use GrayMatterLabs\GeoIp\Exceptions\InvalidIpAddressException;
use GrayMatterLabs\GeoIp\Exceptions\LocationNotFoundException;

final class GeoIp
{
    private static ?Location $default;

    public function __construct(private Locator $locator)
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
     */
    public function locate(string $ip): Location
    {
        if (! $this->isValid($ip)) {
            throw new InvalidIpAddressException($ip);
        }

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
    }

    /**
     * Determine whether the Ip address is valid.
     *
     * @param string $ip
     *
     * @return bool
     */
    private function isValid(string $ip): bool
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
    private function isPrivateRange(string $ip): bool
    {
        return ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
        && ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE);
    }

    /**
     * Get the location to use when an Ip addresses geolocation cannot be found.
     *
     * @param string $ip
     *
     * @return \GrayMatterLabs\GeoIp\Location
     */
    private function getDefaultLocation(string $ip): Location
    {
        return self::$default->clone(ip: $ip, isDefault: true);
    }

    /**
     * Determine whether a default location has been specified.
     *
     * @return bool
     */
    private function hasDefaultLocation(): bool
    {
        return isset(self::$default);
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
        self::$default = $location;
    }
}
