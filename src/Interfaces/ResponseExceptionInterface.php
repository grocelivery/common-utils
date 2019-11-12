<?php

declare(strict_types=1);

namespace Grocelivery\CommonUtils\Interfaces;

/**
 * Interface ResponseExceptionInterface
 * @package Grocelivery\CommonUtils\Interfaces
 */
interface ResponseExceptionInterface
{
    /**
     * @return array
     */
    public function getErrors(): array;

    /**
     * @param array $errors
     * @return ResponseExceptionInterface
     */
    public function setErrors(array $errors): ResponseExceptionInterface;

    /**
     * @param string $error
     * @return ResponseExceptionInterface
     */
    public function addError(string $error): ResponseExceptionInterface;

    /**
     * @param string $message
     * @return ResponseExceptionInterface
     */
    public function setMessage(string $message): ResponseExceptionInterface;
}
