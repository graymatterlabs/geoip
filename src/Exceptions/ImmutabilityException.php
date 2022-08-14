<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp\Exceptions;

use Exception;

class ImmutabilityException extends Exception
{
    public function __construct(object $instance)
    {
        parent::__construct(sprintf('Unable to modify instance of [%s].', get_class($instance)));
    }
}
