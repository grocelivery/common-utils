<?php

namespace Grocelivery\Utils\Middleware;

use Closure;
use Grocelivery\Utils\Exceptions\OAuthKeyLoaderException;
use Grocelivery\Utils\Exceptions\UnauthorizedException;
use Grocelivery\Utils\Services\OAuthKeyLoader;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Throwable;
use Lcobucci\JWT\Signer\Rsa\Sha256;

/**
 * Class Authenticate
 * @package Grocelivery\Utils\Middleware
 */
class Authenticate
{
    /** @var OAuthKeyLoader */
    protected $keyLoader;

    /**
     * Authenticate constructor.
     * @param OAuthKeyLoader $keyLoader
     */
    public function __construct(OAuthKeyLoader $keyLoader)
    {
        $this->keyLoader = $keyLoader;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param null $guard
     * @return mixed
     * @throws OAuthKeyLoaderException
     * @throws UnauthorizedException
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (!$request->hasHeader('Authorization')) {
            throw new UnauthorizedException();
        }

        $accessToken = explode("Bearer ", $request->header('Authorization'))[1];

        try {
            $parsedToken = (new Parser())->parse($accessToken);
        } catch (Throwable $exception) {
            throw new UnauthorizedException();
        }

        $parsedToken->validate(new ValidationData());

        try {
            $parsedToken->verify(new Sha256(), $this->keyLoader->load());
        }  catch (OAuthKeyLoaderException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw new UnauthorizedException();
        }

       return $next($request);
    }
}
