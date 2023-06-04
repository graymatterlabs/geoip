<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp\Exceptions;

use Exception;

final class InvalidIpAddressException extends Exception
{
    public function __construct(string $ip)
    {
        parent::__construct(sprintf('Must provide a valid Ip address [%s].', $ip));
    }
}
