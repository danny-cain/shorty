<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\Routing\RouteManager;

interface RouteManagerConsumer
{
    public function consumeRouteManager(RouteManager $manager);
}