<?php

declare(strict_types=1);

/*
 * This file is part of the Clivern/Events project.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Basket;

use EventSauce\EventSourcing\AggregateRootId;

/**
 * Basket Id.
 */
class BasketId implements AggregateRootId
{
    /** @var string */
    private $id;

    /**
     * Class constructor.
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * To String.
     */
    public function toString(): string
    {
        return $this->id;
    }

    /**
     * From String.
     */
    public static function fromString(string $id): AggregateRootId
    {
        return new static($id);
    }
}
