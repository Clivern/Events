<?php

declare(strict_types=1);

/*
 * This file is part of the Clivern/Events project.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Basket;

use App\Basket\Event\BasketWasInitiated;
use App\Basket\Event\ProductWasAdded;
use App\Basket\Event\ProductWasRemoved;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

/**
 * Basket.
 */
class Basket implements AggregateRoot
{
    use AggregateRootBehaviour;

    /** @var array */
    private $products = [];

    /**
     * Init Basket.
     *
     * @return Basket
     */
    public static function init(BasketId $id): self
    {
        $process = new static($id);
        $process->recordThat(new BasketWasInitiated($id->toString()));

        return $process;
    }

    /**
     * Add Product.
     */
    public function addProduct(int $productId): self
    {
        $this->recordThat(new ProductWasAdded($productId));

        return $this;
    }

    /**
     * Remove Product.
     *
     * @return Basket
     */
    public function removeProduct(int $productId): self
    {
        $this->recordThat(new ProductWasRemoved($productId));

        return $this;
    }

    /**
     * Apply BasketWasInitiated Event.
     *
     * @return void
     */
    public function applyBasketWasInitiated(BasketWasInitiated $event)
    {
        $this->products = [];
    }

    /**
     * Apply ProductWasAdded Event.
     *
     * @return void
     */
    public function applyProductWasAdded(ProductWasAdded $event)
    {
        if (($key = array_search($event->getProductId(), $this->products, true)) === false) {
            $this->products[] = $event->getProductId();
        }
    }

    /**
     * Apply ProductWasRemoved Event.
     *
     * @return void
     */
    public function applyProductWasRemoved(ProductWasRemoved $event)
    {
        if (($key = array_search($event->getProductId(), $this->products, true)) !== false) {
            unset($this->products[$key]);
        }
    }

    /**
     * Get Products.
     */
    public function getProducts(): array
    {
        return array_values($this->products);
    }
}
