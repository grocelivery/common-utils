<?php

declare(strict_types=1);

namespace Grocelivery\HttpUtils\Exceptions;

/**
 * Class OAuthKeyLoaderException
 * @package Grocelivery\HttpUtils\Exceptions
 */
class OAuthKeyLoaderException extends InternalServerException
{
    /** @var string */
    protected $message = "OAuth public key retriever exception.";
}
