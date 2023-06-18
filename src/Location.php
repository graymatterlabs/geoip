<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp;

use ReflectionClass;

class Location
{
    public function __construct(
        protected string  $ip,
        protected ?string $countryCode = null,
        protected ?string $countryName = null,
        protected ?string $stateCode = null,
        protected ?string $stateName = null,
        protected ?string $city = null,
        protected ?string $postalCode = null,
        protected ?string $continent = null,
        protected ?float  $latitude = null,
        protected ?float  $longitude = null,
        protected ?string $timezone = null,
        protected ?string $currency = null,
        protected bool    $isDefault = false,
    ) {
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    public function getStateCode(): ?string
    {
        return $this->stateCode;
    }

    public function getStateName(): ?string
    {
        return $this->stateName;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getContinent(): ?string
    {
        return $this->continent;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function clone(...$properties): Location
    {
        $clone = (new ReflectionClass(static::class))->newInstanceWithoutConstructor();

        foreach (get_object_vars($this) as $property => $value) {
            $value = array_key_exists($property, $properties) ? $properties[$property] : $value;

            $clone->$property = $value;
        }

        return $clone;
    }
}
