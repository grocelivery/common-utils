<?php

declare(strict_types=1);

namespace Grocelivery\Utils\Interfaces;

use Illuminate\Http\JsonResponse;

/**
 * Interface JsonResponseInterface
 * @package Grocelivery\Utils\Interfaces
 */
interface JsonResponseInterface
{
    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param int $code
     * @param string|null $text
     * @return JsonResponseInterface;
     */
    public function setStatusCode(int $code, $text = null): JsonResponseInterface;

//    /**
//     * @param array $data
//     * @return JsonResponseInterface
//     */
//    public function setData(array $data): JsonResponseInterface;
    /**
     * @param string $key
     * @param $value
     * @return JsonResponseInterface
     */
    public function add(string $key, $value): JsonResponseInterface;

    /**
     * @param string $key
     * @param JsonResourceInterface $resource
     * @return JsonResponseInterface
     */
    public function withResource(string $key, JsonResourceInterface $resource): JsonResponseInterface;

    /**
     * @param array $body
     * @return JsonResponseInterface
     */
    public function setBody(array $body): JsonResponseInterface;

    /**
     * @param array $errors
     * @return JsonResponseInterface
     */
    public function setErrors(array $errors): JsonResponseInterface;

    /**
     * @param string $error
     * @return JsonResponseInterface
     */
    public function addError(string $error): JsonResponseInterface;

    /**
     * @param string $message
     * @return JsonResponseInterface
     */
    public function setMessage(string $message): JsonResponseInterface;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return bool
     */
    public function hasMessage(): bool;

    /**
     * @param string $value
     * @return mixed
     */
    public function get(string $value);

    /**
     * @return array
     */
    public function getErrors(): array;

    /**
     * @return int
     */
    public function countErrors(): int;

    /**
     * @return bool
     */
    public function hasErrors(): bool;

    /**
     * @return JsonResponse
     */
    public function send(): JsonResponse;
}
