<?php

namespace Grocelivery\Utils\Interfaces;

/**
 * Interface RestClientInterface
 * @package Grocelivery\Utils\Interfaces
 */
interface RestClientInterface
{
    /**
     * @param $method
     * @param string $uri
     * @param array $options
     * @return JsonResponseInterface
     */
    public function request($method, $uri = "", array $options = []): JsonResponseInterface;

    /**
     * @param array $data
     * @return RestClientInterface
     */
    public function setData(array $data): RestClientInterface;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @param array $headers
     * @return RestClientInterface
     */
    public function setHeaders(array $headers): RestClientInterface;

    /**
     * @param string $key
     * @param string $value
     * @return RestClientInterface
     */
    public function setHeader(string $key, string $value): RestClientInterface;

    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @return string
     */
    public function getHost(): string;
}