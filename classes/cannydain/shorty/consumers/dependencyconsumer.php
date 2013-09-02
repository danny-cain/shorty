<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;

interface DependencyConsumer
{
    public function consumeDependencies(DependencyInjector $dependencies);
}