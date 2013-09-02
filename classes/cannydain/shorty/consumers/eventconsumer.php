<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\Events\EventManager;

interface EventConsumer
{
    public function consumeEventManager(EventManager $eventManager);
}