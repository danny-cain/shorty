<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Shorty\ECommerce\ECommerceManager;

interface ECommerceConsumer extends ConsumerInterface
{
    public function consumeECommerceManager(ECommerceManager $dependency);
}