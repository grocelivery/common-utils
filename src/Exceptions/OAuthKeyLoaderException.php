<?php

declare(strict_types=1);

namespace Grocelivery\Utils\Exceptions;

/**
 * Class OAuthKeyLoaderException
 * @package Grocelivery\Utils\Exceptions
 */
class OAuthKeyLoaderException extends InternalServerException
{
    /** @var string */
    protected $message = "OAuth public key loader exception.";
}
