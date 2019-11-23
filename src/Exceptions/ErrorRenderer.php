<?php

namespace Grocelivery\HttpUtils\Exceptions;

use Exception;
use Grocelivery\HttpUtils\Interfaces\JsonResponseInterface as JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ErrorRenderer
 * @package Grocelivery\HttpUtils\Exceptions
 */
class ErrorRenderer
{
    /** @var JsonResponse */
    protected $response;

    /**
     * Handler constructor.
     * @param JsonResponse $response
     */
    public function __construct(JsonResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @param Request $request
     * @param Exception $exception
     * @return JsonResponse
     */
    public function render(Request $request, Exception $exception): JsonResponse
    {
        if ($exception instanceof InternalServerException) {

            if ($exception->hasErrors()) {
                $this->response->setErrors($exception->getErrors());
            } else {
                $this->response->addError($exception->getMessage());
            }

            return $this->response->setStatusCode($exception->getCode());
        }

        if ($exception instanceof ValidationException) {
            foreach ($exception->errors() as $errors) {
                foreach ($errors as $error) {
                    $this->response->addError($error);
                }
            }

            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

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
