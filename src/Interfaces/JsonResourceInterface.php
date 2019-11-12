<?php

declare(strict_types=1);

namespace Grocelivery\CommonUtils\Interfaces;

use Illuminate\Support\Collection;

/**
 * Interface JsonResourceInterface
 * @package Grocelivery\CommonUtils\Interfaces
 */
interface JsonResourceInterface
{
    /**
     * @return array
     */
    function toArray(): array;
}