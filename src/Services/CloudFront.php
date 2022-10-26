<?php

declare(strict_types=1);

namespace GrayMatterLabs\GeoIp\Services;

use GrayMatterLabs\GeoIp\Contracts\Locator;
use GrayMatterLabs\GeoIp\Location;

/*
 * A runtime-only Locator service leveraging CloudFront's 'additional geolocation headers'
 * managed transform. This service provides a location only in the context of
 * a web request routed through CloudFront.
 *
 * To learn more including how to enable these headers, read here:
 * https://aws.amazon.com/about-aws/whats-new/2020/07/cloudfront-geolocation-headers/
 */
class CloudFront implements Locator
{
    public function locate(string $ip): Location
    {
        return new Location(
            ip: $ip,
            countryCode: $this->castHeaderIfExists('CloudFront-Viewer-Country'),
            countryName: $this->castHeaderIfExists('CloudFront-Viewer-Country-Name'),
            stateCode: $this->castHeaderIfExists('CloudFront-Viewer-Country-Region'),
            stateName: $this->castHeaderIfExists('CloudFront-Viewer-Country-Region-Name'),
            city: $this->castHeaderIfExists('CloudFront-Viewer-City'),
            postalCode: $this->castHeaderIfExists('CloudFront-Viewer-Postal-Code'),
            latitude: $this->castHeaderIfExists('CloudFront-Viewer-Latitude', 'float'),
            longitude: $this->castHeaderIfExists('CloudFront-Viewer-Longitude', 'float'),
        );
    }

    private function castHeaderIfExists(string $header, string $type = 'string'): mixed
    {
        $header = 'HTTP_' . strtoupper($header);
        if ($value = $_SERVER[$header] ?? null) {
            settype($value, $type);
        }

        return $value;
    }
}
