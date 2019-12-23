<?php

declare(strict_types=1);

namespace Grocelivery\Utils\Models;

/**
 * Class User
 * @package Grocelivery\Utils\Models
 */
class User
{
    /** @var string */
    protected $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }
}