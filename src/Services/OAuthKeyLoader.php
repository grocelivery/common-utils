<?php

namespace Grocelivery\HttpUtils\Services;

use Grocelivery\HttpUtils\Exceptions\OAuthKeyLoaderException;
use GuzzleHttp\Client;
use Grocelivery\HttpUtils\Responses\JsonResponse;
use Throwable;

/**
 * Class OAuthKeyRetriever
 * @package Grocelivery\HttpUtils\Services
 */
class OAuthKeyLoader
{
    /** @var Client */
    protected $httpClient;
    /**
     * @var array
     */
    protected $config = [];

    /**
     * OAuthKeyLoader constructor.
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return string
     * @throws OAuthKeyLoaderException
     */
    public function load(): string
    {
        if ($this->shouldLoadFromApi()) {
            return $this->loadFromApi();
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
            $response = $this->httpClient->get(config('http-utils.oauth_key.api.url'));
        } catch (Throwable $exception) {
            throw new OAuthKeyLoaderException('Unable to load OAuth public key from API');
        }

        $response = JsonResponse::fromJsonString($response->getBody()->getContents());
        return $response->get('key');
    }

    /**
     * @return string
     * @throws OAuthKeyLoaderException
     */
    protected function loadFromFile(): string
    {
        try {
            return file_get_contents(base_path() . config('http-utils.oauth_key.file.path'));
        } catch (Throwable $exception) {
            throw new OAuthKeyLoaderException('Unable to load OAuth public key from file');
        }
    }

    /**
     * @return bool
     */
    protected function shouldLoadFromApi(): bool
    {
        return !is_null(config('http-utils.oauth_key.api'));
    }

    /**
     * @return bool
     */
    protected function shouldLoadFromFile(): bool
    {
        return !is_null(config('http-utils.oauth_key.file'));
    }
}