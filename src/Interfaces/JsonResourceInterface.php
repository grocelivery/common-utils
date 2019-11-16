<?php

declare(strict_types=1);

namespace Grocelivery\HttpUtils\Interfaces;

/**
 * Interface JsonResourceInterface
 * @package Grocelivery\HttpUtils\Interfaces
 */
interface JsonResourceInterface
{
    /**
     * @return array
     */
    public function map(): array;

    /**
     * @return array
     */
    function toArray(): array;
}