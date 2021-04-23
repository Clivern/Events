<?php

declare(strict_types=1);

/*
 * This file is part of the Clivern/Events project.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Repository;

use App\Entity\User;
use App\Event\UserCreated;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * User Repository.
 */
class UserRepository extends ServiceEntityRepository
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * Class constructor.
     */
    public function __construct(ManagerRegistry $registry, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($registry, User::class);
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Find One by Name.
     */
    public function findOneByName(string $name): ?User
    {
        $user = $this->findOneBy([
            'name' => $name,
        ]);

        return !empty($user) ? $user : null;
    }

    /**
     * Create a new User.
     */
    public function createOne(string $name): ?User
    {
        $user = new User();
        $user->setName($name);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $event = new UserCreated($user);
        $this->eventDispatcher->dispatch($event, UserCreated::NAME);

        return $user;
    }
}
