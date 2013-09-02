<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\Helpers\SessionHelper;

interface SessionConsumer
{
    public function consumeSession(SessionHelper $session);
}