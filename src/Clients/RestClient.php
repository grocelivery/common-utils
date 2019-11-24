<?php

namespace Grocelivery\Utils\Clients;

use Grocelivery\Utils\Exceptions\RestClientException;
use Grocelivery\Utils\Interfaces\JsonResponseInterface;
use Grocelivery\Utils\Interfaces\PropagatesAccessToken;
use Grocelivery\Utils\Interfaces\RestClientInterface;
use Grocelivery\Utils\Responses\JsonResponse;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Psr\Http\Message\UriInterface;

/**
 * Class RestClient
 * @package Grocelivery\Utils\Clients
 * @method JsonResponseInterface post(string|UriInterface $uri, array $options = [])
 * @method JsonResponseInterface get(string|UriInterface $uri, array $options = [])
 * @method JsonResponseInterface put(string|UriInterface $uri, array $options = [])
 * @method JsonResponseInterface patch(string|UriInterface $uri, array $options = [])
 * @method JsonResponseInterface delete(string|UriInterface $uri, array $options = [])
 */
class RestClient extends GuzzleClient implements RestClientInterface
{
    /** @var array */
    protected $data = [];
    /** @var array */
    protected $headers = [];
    /** @var Request */
    protected $request;

    /**
     * RestClient constructor.
     * @param Request $request
     * @param array $config
     */
    public function __construct(Request $request, array $config = [])
    {
        parent::__construct($config);
        $this->request = $request;
    }

    /**
     * @param $method
     * @param string $path
     * @param array $options
     * @return JsonResponseInterface
     * @throws RestClientException
     */
    public function request($method, $path = "", array $options = []): JsonResponseInterface
    {
        try {
            $response = $this->requestAsync($method, $this->buildUri($path), $this->buildOptions())->wait();
        } catch (GuzzleException $exception) {
            throw new RestClientException($exception->getMessage());
        }

        return JsonResponse::fromJsonString($response->getBody()->getContents());
    }

    /**
     * @param array $data
     * @return RestClientInterface
     */
    public function setData(array $data): RestClientInterface
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $headers
     * @return RestClientInterface
     */
    public function setHeaders(array $headers): RestClientInterface
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return RestClientInterface
     */
    public function setHeader(string $key, string $value): RestClientInterface
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * @param string $accessToken
     * @return RestClientInterface
     */
    public function setAccessToken(string $accessToken): RestClientInterface
    {
        $this->headers['Authorization'] = "Bearer $accessToken";
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return array_merge($this->getDefaultHeaders(), $this->headers);
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return '';
    }

    /**
     * @return array
     */
    protected function getDefaultHeaders(): array
    {
        $headers = [
            "Accept" => "application/json",
            "Content-Type" => "application/json",
        ];

        if ($this instanceof PropagatesAccessToken) {
            $headers['Authorization'] = $this->request->headers->get('Authorization') ?? '';
        }

        return $headers;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function buildUri(string $path)
    {
        return $this->getHost() . $path;
    }

    /**
     * @return array
     */
    protected function buildOptions(): array
    {
        return [
            RequestOptions::SYNCHRONOUS => true,
            'headers' => $this->getHeaders(),
            'json' => $this->getData(),
        ];
    }
}