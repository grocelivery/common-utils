<?php

namespace Grocelivery\HttpUtils\Exceptions;

use Exception;
use Grocelivery\HttpUtils\Interfaces\JsonResponseInterface;
use Grocelivery\HttpUtils\Responses\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * Class ErrorRenderer
 * @package Grocelivery\HttpUtils\Exceptions
 */
class ErrorRenderer
{
    /** @var JsonResponse */
    protected $response;
    /** @var array */
    protected $additionallyHandle = [];

    /**
     * @param Request $request
     * @param Exception $exception
     * @return JsonResponseInterface
     */
    public function render(Request $request, Exception $exception): JsonResponseInterface
    {
        $this->response = new JsonResponse();

        if ($exception instanceof InternalServerException) {
            return $this->renderInternalServerException($exception);
        }

        if ($exception instanceof ValidationException) {
            return $this->renderValidationException($exception);
        }

        foreach ($this->additionallyHandle as $exceptionClass => $response) {
            if ($exception instanceof $exceptionClass) {
                return $this->response
                    ->setStatusCode($response['statusCode'])
                    ->addError($response['error'] ?? $exception->getMessage());
            }
        }

        return $this->renderFallbackException($exception);
    }

    /**
     * @param string $exceptionClass
     * @param int $statusCode
     * @param string $error
     */
    public function additionallyHandle(string $exceptionClass, int $statusCode, string $error = null): void
    {
        $this->additionallyHandle[$exceptionClass] = [
            'statusCode' => $statusCode,
            'error' => $error,
        ];
    }

    /**
     * @param InternalServerException $exception
     * @return JsonResponseInterface
     */
    protected function renderInternalServerException(InternalServerException $exception): JsonResponseInterface
    {
        if ($exception->hasErrors()) {
            $this->response->setErrors($exception->getErrors());
        } else {
            $this->response->addError($exception->getMessage());
        }

        return $this->response->setStatusCode($exception->getCode());
    }

    /**
     * @param ValidationException $exception
     * @return JsonResponseInterface
     */
    protected function renderValidationException(ValidationException $exception): JsonResponseInterface
    {
        foreach ($exception->errors() as $errors) {
            foreach ($errors as $error) {
                $this->response->addError($error);
            }
        }

        return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Throwable $exception
     * @return JsonResponseInterface
     */
    protected function renderFallbackException(Throwable $exception): JsonResponseInterface
    {
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        $error = '';

        if ($exception instanceof NotFoundHttpException) {
            $status = Response::HTTP_NOT_FOUND;
            $error = 'Route not found.';
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            $status = Response::HTTP_METHOD_NOT_ALLOWED;
            $error = 'Method not allowed.';
        }

        if (empty($error)) {
            $error = !empty($exception->getMessage()) ? $exception->getMessage() : 'Internal server error.';
        }

        return $this->response
            ->setStatusCode($status)
            ->addError($error);
    }
}
