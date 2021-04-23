<?php

declare(strict_types=1);

/*
 * This file is part of the Clivern/Events project.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Basket;

use EventSauce\EventSourcing\Message;

/**
 * Message Dispatcher.
 */
class MessageDispatcher
{
    /**
     * Dispatch messages to consumers.
     *
     * @param array $messages
     *
     * @return void
     */
    public function dispatch(Message ...$messages)
    {
        //..
    }
}
