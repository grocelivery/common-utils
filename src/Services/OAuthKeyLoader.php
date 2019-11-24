<?php

namespace Grocelivery\Utils\Services;

use Carbon\Carbon;
use Grocelivery\Utils\Clients\RestClient;
use Grocelivery\Utils\Exceptions\OAuthKeyLoaderException;
use Grocelivery\Utils\Exceptions\RestClientException;
use Grocelivery\Utils\Responses\JsonResponse;
use Illuminate\Contracts\Cache\Repository as Cache;
use Psr\SimpleCache\InvalidArgumentException;
use Throwable;

/**
 * Class OAuthKeyLoader
 * @package Grocelivery\Utils\Services
 */
class OAuthKeyLoader
{
    /** @var RestClient */
    protected $restClient;
    /** @var Cache */
    protected $cache;

    /**
     * OAuthKeyLoader constructor.
     * @param RestClient $restClient
     * @param Cache $cache
     */
    public function __construct(RestClient $restClient, Cache $cache)
    {
        $this->restClient = $restClient;
        $this->cache = $cache;
    }

    /**
     * @return string
     * @throws OAuthKeyLoaderException
     * @throws InvalidArgumentException
     */
    public function load(): string
    {
        if ($this->shouldLoadFromApi()) {
            if ($this->cache->has('oauth-key')) {
                return $this->cache->get('oauth-key');
            } else {
                $key = $this->loadFromApi();
                $this->cache->set('oauth-key', $key, $this->getKeyCacheTTL());
                return $key;
            }
        }

        if ($this->shouldLoadFromFile()) {
            return $this->loadFromFile();
        }

        throw new OAuthKeyLoaderException("OAuth public key is not specified.");
    }

    /**
     * @return string
     * @throws OAuthKeyLoaderException
     */
    protected function loadFromApi(): string
    {
        try {
            /** @var JsonResponse $response */
            $response = $this->restClient->get(config('grocelivery.oauth_key.api.url'));
        } catch (RestClientException $exception) {
            throw new OAuthKeyLoaderException('Unable to load OAuth public key from API');
        }

        return $response->get('key');
    }

    /**
     * @return string
     * @throws OAuthKeyLoaderException
     */
    protected function loadFromFile(): string
    {
        try {
            return file_get_contents(base_path() . config('grocelivery.oauth_key.file.path'));
        } catch (Throwable $exception) {
            throw new OAuthKeyLoaderException('Unable to load OAuth public key from file');
        }
    }

    /**
     * @return bool
     */
    protected function shouldLoadFromApi(): bool
    {
        return !is_null(config('grocelivery.oauth_key.api'));
    }

    /**
     * @return bool
     */
    protected function shouldLoadFromFile(): bool
    {
        return !is_null(config('grocelivery.oauth_key.file'));
    }

    /**
     * @return Carbon
     */
    protected function getKeyCacheTTL(): Carbon
    {
        return Carbon::now()->addDay();
    }
}