<?php

declare(strict_types=1);

/*
 * This file is part of the Clivern/Events project.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Basket\Event;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

/**
 * BasketWasInitiated Event.
 */
class BasketWasInitiated implements SerializablePayload
{
    /** @var int */
    private $basketId;

    /**
     * Class constructor.
     */
    public function __construct(string $basketId)
    {
        $this->basketId = $basketId;
    }

    /**
     * Get Product Id.
     *
     * @return int
     */
    public function getBacketId(): string
    {
        return $this->basketId;
    }

    /**
     * Get Payload.
     */
    public function toPayload(): array
    {
        return ['basketId' => $this->basketId];
    }

    /**
     * Load from a payload.
     */
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self($payload['basketId']);
    }
}
