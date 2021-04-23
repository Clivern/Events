<?php

declare(strict_types=1);

/*
 * This file is part of the Clivern/Events project.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Basket\Event;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

/**
 * ProductWasRemoved Event.
 */
class ProductWasRemoved implements SerializablePayload
{
    /** @var int */
    private $productId;

    /**
     * Class constructor.
     */
    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }

    /**
     * Get Product Id.
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * Get Payload.
     */
    public function toPayload(): array
    {
        return ['productId' => $this->productId];
    }

    /**
     * Load from a payload.
     */
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self($payload['productId']);
    }
}
