<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp;

use GrayMatterLabs\GeoIp\Exceptions\ImmutabilityException;

/**
 * @property-read string $ip
 * @property-read string|null $country
 * @property-read string|null $country_name
 * @property-read string|null $state
 * @property-read string|null $state_name
 * @property-read string|null $city
 * @property-read string|null $postal_code
 * @property-read float|null $latitude
 * @property-read float|null $longitude
 * @property-read bool $is_default
 */
class ImmutableLocation extends Location
{
    protected static bool $strict = false;

    public function setAttribute(string $name, mixed $value): void
    {
        if (static::$strict) {
            throw new ImmutabilityException($this);
        }
    }

    public function unsetAttribute(string $name): void
    {
        if (static::$strict) {
            throw new ImmutabilityException($this);
        }
    }

    public static function setStrict(bool $strict): void
    {
        static::$strict = $strict;
    }
}
