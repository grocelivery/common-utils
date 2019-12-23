<?php

declare(strict_types=1);

namespace Grocelivery\Utils\Resources;

use Illuminate\Contracts\Support\Arrayable;
use ArrayAccess;

/**
 * Class ResourceArray
 * @package Grocelivery\Utils\Resources
 */
class ResourceArray implements Arrayable, ArrayAccess
{
    /** @var array */
    protected $array = [];

    /**
     * ResourceArray constructor.
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->array;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->array[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->array[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->array[$offset]);
    }
}

