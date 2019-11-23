<?php

declare(strict_types=1);

namespace Grocelivery\HttpUtils\Exceptions;

use Illuminate\Http\Response;

/**
 * Class UnauthorizedException
 * @package Grocelivery\HttpUtils\Exceptions
 */
class UnauthorizedException extends InternalServerException
{
    /** @var string */
    protected $message = "Unauthorized.";
    /** @var int */
    protected $code = Response::HTTP_UNAUTHORIZED;
}
