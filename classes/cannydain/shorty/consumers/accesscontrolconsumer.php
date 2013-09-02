<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\Helpers\AccessControl\AccessControlHelper;

interface AccessControlConsumer
{
    public function consumeAccessControlHelper(AccessControlHelper $helper);
}