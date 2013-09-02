<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;

interface RouterConsumer
{
    public function consumeRouter(RouterInterface $router);
}