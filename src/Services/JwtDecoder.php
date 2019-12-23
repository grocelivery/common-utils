<?php

declare(strict_types=1);

namespace Grocelivery\Utils\Services;

use Grocelivery\Utils\Models\User;
use Lcobucci\JWT\Parser;

/**
 * Class JwtDecoder
 * @package Grocelivery\Utils\Services
 */
class JwtDecoder
{
    /**
     * @param string $jwt
     * @return User
     */
    public function fetchUser(string $jwt): User
    {
        $jwt = $this->removeBearerPrefix($jwt);
        $parser = (new Parser())->parse($jwt);

        $user = new User();
        $user->setId($parser->getClaim('sub'));

        return $user;
    }

    /**
     * @param string $jwt
     * @return string
     */
    protected function removeBearerPrefix(string $jwt): string
    {
        return str_replace('Bearer ', '', $jwt);
    }
}