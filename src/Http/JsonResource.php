<?php

declare(strict_types=1);

namespace Grocelivery\CommonUtils\Http;

use Grocelivery\CommonUtils\Interfaces\JsonResourceInterface;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class JsonResource
 * @package Grocelivery\CommonUtils\Http
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
    protected function map(): array
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