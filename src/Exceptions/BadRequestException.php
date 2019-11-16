<?php

declare(strict_types=1);

namespace Grocelivery\HttpUtils\Exceptions;

use Illuminate\Http\Response;

/**
 * Class BadRequestException
 * @package Grocelivery\HttpUtils\Exceptions
 */
class BadRequestException extends InternalServerException
{
    /** @var string */
    protected $message = "Bad request.";
    /** @var int */
    protected $code = Response::HTTP_BAD_REQUEST;
}
