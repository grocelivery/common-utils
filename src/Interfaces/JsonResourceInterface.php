<?php

declare(strict_types=1);

namespace Grocelivery\CommonUtils\Interfaces;

/**
 * Interface JsonResourceInterface
 * @package Grocelivery\CommonUtils\Interfaces
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