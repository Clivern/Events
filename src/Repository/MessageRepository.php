<?php

declare(strict_types=1);

/*
 * This file is part of the Clivern/Events project.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Repository;

use App\Entity\Message as MessageEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;

/**
 * Message Repository.
 */
class MessageRepository extends ServiceEntityRepository
{
    /**
     * Class constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageEntity::class);
    }

    /**
     * Persist Messages.
     *
     * @param array $messages
     *
     * @return void
     */
    public function persist(Message ...$messages)
    {
        foreach ($messages as $message) {
            $messageEntity = new MessageEntity();
            $messageEntity->setAggregateId($message->aggregateRootId()->toString())
                ->setHeaders(json_encode($message->headers()))
                ->setPayload(json_encode($message->event()->toPayload()));

            $this->getEntityManager()->persist($messageEntity);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Retrieve all messages by aggregate Id.
     */
    public function retrieveAll(AggregateRootId $id): array
    {
        $messages = [];

        $msgs = $this->findBy(
            ['aggregateId' => $id->toString()],
            ['id' => 'ASC']
        );

        foreach ($msgs as $msg) {
            $headers = json_decode($msg->getHeaders(), true);
            $event = $headers[Header::EVENT_TYPE]::fromPayload(json_decode($msg->getPayload(), true));

            $messages[] = new Message($event, $headers);
        }

        return $messages;
    }
}
