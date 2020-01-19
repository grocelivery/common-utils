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
use Psr\SimpleCache\InvalidArgumentException;

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
     * @throws InvalidArgumentException
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (!$request->hasHeader('Authorization')) {
            throw new UnauthorizedException();
        }

        $accessToken = explode("Bearer ", $request->header('Authorization'))[1] ?? '';

        try {
            $parsedToken = (new Parser())->parse($accessToken);
        } catch (Throwable $exception) {
            throw new UnauthorizedException();
        }

        if (!$parsedToken->validate(new ValidationData())) {
            throw new UnauthorizedException();
        }

        $publicKey = $this->keyLoader->load();

        if (!$parsedToken->verify(new Sha256(), $publicKey)) {
            throw new UnauthorizedException();
        }

       return $next($request);
    }
}
