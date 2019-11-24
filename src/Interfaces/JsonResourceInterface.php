<?php

declare(strict_types=1);

namespace Grocelivery\Utils\Interfaces;

/**
 * Interface JsonResourceInterface
 * @package Grocelivery\Utils\Interfaces
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