<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\Helpers\UserHelper;

interface UserConsumer
{
    public function consumerUserHelper(UserHelper $helper);
}