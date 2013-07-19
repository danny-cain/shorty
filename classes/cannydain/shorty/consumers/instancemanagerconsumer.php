<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Shorty\InstanceManager\InstanceManager;

interface InstanceManagerConsumer extends ConsumerInterface
{
    public function consumeInstanceManager(InstanceManager $dependency);
}