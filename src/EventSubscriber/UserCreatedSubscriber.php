<?php

declare(strict_types=1);

/*
 * This file is part of the Clivern/Events project.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\EventSubscriber;

use App\Event\UserCreated;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserCreatedSubscriber implements EventSubscriberInterface
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function onUserCreated(UserCreated $event)
    {
        $this->logger->info(sprintf('User created with id %d', $event->getUser()->getId()));
    }

    public static function getSubscribedEvents()
    {
        return [
            UserCreated::NAME => 'onUserCreated',
        ];
    }
}
