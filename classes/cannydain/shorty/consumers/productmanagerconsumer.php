<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\ECommerce\Products\ProductManager;

interface ProductManagerConsumer
{
    public function consumeProductManager(ProductManager $manager);
}