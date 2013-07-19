<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;

interface DependencyConsumer extends ConsumerInterface
{
    public function consumeDependencyInjector(DependencyInjector $dependency);
}