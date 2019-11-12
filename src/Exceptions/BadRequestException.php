<?php

declare(strict_types=1);

namespace Grocelivery\CommonUtils\Exceptions;

use Illuminate\Http\Response;

/**
 * Class BadRequestException
 * @package Grocelivery\CommonUtils\Exceptions
 */
class BadRequestException extends InternalServerException
{
    /** @var string */
    protected $message = "Bad request.";
    /** @var int */
    protected $code = Response::HTTP_BAD_REQUEST;
}
