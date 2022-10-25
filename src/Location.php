<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp;

use ReflectionClass;

class Location
{
    public function __construct(
        public readonly string $ip,
        public readonly ?string $countryCode = null,
        public readonly ?string $countryName = null,
        public readonly ?string $stateCode = null,
        public readonly ?string $stateName = null,
        public readonly ?string $city = null,
        public readonly ?string $postalCode = null,
        public readonly ?float $latitude = null,
        public readonly ?float $longitude = null,
        public readonly bool $isDefault = false,
    ) {
    }

    public function clone(...$properties): static
    {
        $clone = (new ReflectionClass(static::class))->newInstanceWithoutConstructor();

        foreach (get_object_vars($this) as $property => $value) {
            $value = array_key_exists($property, $properties) ? $properties[$property] : $value;

            $clone->$property = $value;
        }

        return $clone;
    }
}
