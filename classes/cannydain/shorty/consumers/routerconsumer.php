<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;

interface RouterConsumer extends ConsumerInterface
{
    public function consumeRouter(RouterInterface $dependency);
}