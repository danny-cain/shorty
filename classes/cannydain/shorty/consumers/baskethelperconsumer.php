<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\ECommerce\Basket\BasketHelperInterface;

interface BasketHelperConsumer
{
    public function consumeBasketHelper(BasketHelperInterface $helper);
}