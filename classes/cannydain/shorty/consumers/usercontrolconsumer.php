<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Shorty\UserControl\UserControl;

interface UserControlConsumer extends ConsumerInterface
{
    public function consumeUserController(UserControl $dependency);
}