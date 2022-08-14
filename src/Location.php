<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp;

use ArrayAccess;

/**
 * @property string $ip
 * @property string|null $country
 * @property string|null $country_name
 * @property string|null $state
 * @property string|null $state_name
 * @property string|null $city
 * @property string|null $postal_code
 * @property float|null $latitude
 * @property float|null $longitude
 * @property bool $is_default
 */
class Location implements ArrayAccess
{
    public function __construct(protected array $attributes)
    {
    }

    public function isDefault(): bool
    {
        return (bool) $this->getAttribute('is_default', false);
    }

    public function clone(array $attributes = []): static
    {
        return new static(array_merge($this->toArray(), $attributes));
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    public function setAttribute(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function unsetAttribute(string $name): void
    {
        unset($this->attributes[$name]);
    }

    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    public function __get(string $name)
    {
        return $this->getAttribute($name);
    }

    public function __set(string $name, $value): void
    {
        $this->setAttribute($name, $value);
    }

    public function __isset(string $name): bool
    {
        return $this->hasAttribute($name);
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->hasAttribute($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->getAttribute($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->setAttribute($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->unsetAttribute($offset);
    }
}
