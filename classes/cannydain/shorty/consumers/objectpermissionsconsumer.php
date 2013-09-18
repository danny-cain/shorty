<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\ObjectPermissions\ObjectPermissionsManagerInterface;

interface ObjectPermissionsConsumer
{
    public function consumeObjectPermissionsManager(ObjectPermissionsManagerInterface $manager);
}