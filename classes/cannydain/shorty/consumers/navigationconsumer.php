<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Shorty\Navigation\NavigationProvider;

interface NavigationConsumer extends ConsumerInterface
{
    public function consumeNavigationProvider(NavigationProvider $dependency);
}