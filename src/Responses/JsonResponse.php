<?php

declare(strict_types=1);

namespace Grocelivery\HttpUtils\Responses;

use Grocelivery\HttpUtils\Interfaces\JsonResourceInterface as JsonResource;
use Grocelivery\HttpUtils\Interfaces\JsonResponseInterface;
use Illuminate\Http\JsonResponse as BaseResponse;

/**
 * Class JsonResponse
 * @package Grocelivery\HttpUtils\Http
 */
class JsonResponse extends BaseResponse implements JsonResponseInterface
{
    /** @var array */
    private $body = [];
    /** @var array */
    private $errors = [];

    /**
     * @param null $data
     * @param int $status
     * @param array $headers
     * @return JsonResponseInterface
     */
    public static function fromJsonString($data = null, $status = 200, $headers = []): JsonResponseInterface
    {
        $data = json_decode($data, true);
        $response = new JsonResponse($data, $status, $headers);
        $response->setBody($data);
        return $response;
    }

    /**
     * @param array $data
     * @return JsonResponseInterface
     */
    public static function fromArray(array $data): JsonResponseInterface
    {
        return static::fromJsonString(json_encode($data));
    }

    /**
     * @return array
     */
    public function all(): array
    {
        if (!empty($this->body)) {
            $data['body'] = $this->body;
        }

        $data['errors'] = $this->errors;

        return $data;
    }

    /**
     * @param int $code
     * @param null $text
     * @return JsonResponseInterface
     */
    public function setStatusCode(int $code, $text = null): JsonResponseInterface
    {
        parent::setStatusCode($code, $text);
        return $this;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @param array $body
     * @return JsonResponseInterface
     */
    public function setBody(array $body): JsonResponseInterface
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param string $key
     * @param $value
     * @return JsonResponseInterface
     */
    public function add(string $key, $value): JsonResponseInterface
    {
        $this->body[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @param JsonResource $resource
     * @return JsonResponseInterface
     */
    public function withResource(string $key, JsonResource $resource): JsonResponseInterface
    {
        $this->body[$key] = $resource->map();
        return $this;
    }

    /**
     * @param string $message
     * @return JsonResponseInterface
     */
    public function setMessage(string $message): JsonResponseInterface
    {
        $this->body['message'] = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->body['message'];
    }

    /**
     * @return bool
     */
    public function hasMessage(): bool
    {
        return !empty($this->body['message']);
    }

    /**
     * @param array $errors
     * @return JsonResponseInterface
     */
    public function setErrors(array $errors): JsonResponseInterface
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @param string $error
     * @return JsonResponseInterface
     */
    public function addError(string $error): JsonResponseInterface
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->body[$key];
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return int
     */
    public function countErrors(): int
    {
        return count($this->getErrors());
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return $this->countErrors() !== 0;
    }

    /**
     * @return BaseResponse
     */
    public function send(): BaseResponse
    {
        $this->prepareData();
        return parent::send();
    }

    protected function prepareData(): void
    {
        $this->data = json_encode($this->all(), $this->encodingOptions);
        $this->update();
    }
}
