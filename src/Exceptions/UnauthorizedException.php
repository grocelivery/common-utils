<?php

declare(strict_types=1);

namespace Grocelivery\Utils\Exceptions;

use Illuminate\Http\Response;

/**
 * Class UnauthorizedException
 * @package Grocelivery\Utils\Exceptions
 */
class UnauthorizedException extends InternalServerException
{
    /** @var string */
    protected $message = "Unauthorized.";
    /** @var int */
    protected $code = Response::HTTP_UNAUTHORIZED;
}
