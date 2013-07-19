<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Lib\GUIDS\GUIDManagerInterface;

interface GUIDManagerConsumer extends ConsumerInterface
{
    public function consumeGUIDManager(GUIDManagerInterface $dependency);
}