<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp\Exceptions;

use Exception;

final class LocationNotFoundException extends Exception
{
    public function __construct(string $ip)
    {
        parent::__construct(sprintf('Unable to locate Ip address [%s].', $ip));
    }
}
