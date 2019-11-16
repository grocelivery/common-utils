<?php

declare(strict_types=1);

namespace Grocelivery\HttpUtils\Resources;

use Grocelivery\HttpUtils\Interfaces\JsonResourceInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * Class JsonResource
 * @package Grocelivery\HttpUtils\Http
 */
abstract class JsonResource implements JsonResourceInterface
{
    /** @var Arrayable */
    protected $resource;

    /**
     * Resource constructor.
     * @param Arrayable $resource
     */
    public function __construct(Arrayable $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return array
     */
    public function map(): array
    {
        $data = [];

        if ($this->resource instanceof Collection) {
            foreach ($this->resource as $resource) {
                $this->resource = $resource;
                $data[] = $this->toArray();
            }
        } else {
            $data = $this->toArray();
        }

        return $data;
    }
}