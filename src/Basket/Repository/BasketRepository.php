<?php

declare(strict_types=1);

/*
 * This file is part of the Clivern/Events project.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Basket\Repository;

use App\Basket\Basket;
use App\Basket\MessageDispatcher;
use App\Repository\MessageRepository;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\Time\SystemClock;

/**
 * Basket Repository.
 */
class BasketRepository
{
    /** @var MessageRepository */
    private $messageRepository;

    /** @var MessageDispatcher */
    private $messageDispatcher;

    /**
     * Class constructor.
     */
    public function __construct(MessageRepository $messageRepository, MessageDispatcher $messageDispatcher)
    {
        $this->messageRepository = $messageRepository;
        $this->messageDispatcher = $messageDispatcher;
    }

    /**
     * Persist messages.
     *
     * @return void
     */
    public function persist(AggregateRoot $aggregate)
    {
        $events = $aggregate->releaseEvents();
        $aggregateRootId = $aggregate->aggregateRootId();

        $headers = [Header::AGGREGATE_ROOT_ID => $aggregateRootId];
        $messages = array_map(function (object $event) use ($headers) {
            $headers[Header::EVENT_TYPE] = \get_class($event);

            $headers[Header::TIME_OF_RECORDING] = (string) (new SystemClock(
                new \DateTimeZone('UTC')
            ))->pointInTime();

            return new Message($event, $headers);
        }, $events);

        $this->messageRepository->persist(...$messages);
        $this->messageDispatcher->dispatch(...$messages);
    }

    /**
     * Retrieve messages by aggregate Id.
     */
    public function retrieve(AggregateRootId $id): Basket
    {
        return Basket::reconstituteFromEvents($id, $this->retrieveAllEvents($id));
    }

    /**
     * Retrieve All Events.
     *
     * @return Generator
     */
    private function retrieveAllEvents(AggregateRootId $id): \Generator
    {
        $messages = $this->messageRepository->retrieveAll($id);

        foreach ($messages as $message) {
            yield $message->event();
        }
    }
}
