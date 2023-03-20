<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp;

use ReflectionClass;

readonly class Location
{
    public function __construct(
        public string  $ip,
        public ?string $countryCode = null,
        public ?string $countryName = null,
        public ?string $stateCode = null,
        public ?string $stateName = null,
        public ?string $city = null,
        public ?string $postalCode = null,
        public ?string $continent = null,
        public ?float  $latitude = null,
        public ?float  $longitude = null,
        public ?string $timezone = null,
        public ?string $currency = null,
        public bool    $isDefault = false,
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
