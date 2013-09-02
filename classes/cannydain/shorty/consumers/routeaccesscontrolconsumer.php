<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\RouteAccessControl\RouteAccessControlInterface;

interface RouteAccessControlConsumer
{
    public function consumeRouteAccessControl(RouteAccessControlInterface $rac);
}