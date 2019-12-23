<?php

declare(strict_types=1);

namespace Grocelivery\Utils\Exceptions;

/**
 * Class GeolocalizerClientException
 * @package Grocelivery\Utils\Exceptions
 */
class GeolocalizerClientException extends RestClientException
{
    /** @var string */
    protected $message = "Internal request to geolocation service failed. Please try again later.";
}