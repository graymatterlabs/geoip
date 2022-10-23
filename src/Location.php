<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp;

use ReflectionClass;

class Location
{
    public function __construct(
        public readonly string $ip,
        public readonly ?string $country = null,
        public readonly ?string $countryName = null,
        public readonly ?string $state = null,
        public readonly ?string $stateName = null,
        public readonly ?string $city = null,
        public readonly ?string $postalCode = null,
        public readonly ?float $latitude = null,
        public readonly ?float $longitude = null,
        public readonly bool $isDefault = false,
    ) {
    }

    public function clone(...$values): static
    {
        $clone = (new ReflectionClass(static::class))->newInstanceWithoutConstructor();

        foreach (get_object_vars($this) as $objectField => $objectValue) {
            $objectValue = array_key_exists($objectField, $values) ? $values[$objectField] : $objectValue;

            $clone->$objectField = $objectValue;
        }

        return $clone;
    }
}
