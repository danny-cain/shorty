<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\Geo\AddressManager;

interface AddressManagerConsumer
{
    public function consumeAddressManager(AddressManager $manager);
}