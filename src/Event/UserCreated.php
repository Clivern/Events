<?php

declare(strict_types=1);

/*
 * This file is part of the Event project.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event as SymfonyEvent;

class UserCreated extends SymfonyEvent
{
    public const NAME = 'user.created';

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
