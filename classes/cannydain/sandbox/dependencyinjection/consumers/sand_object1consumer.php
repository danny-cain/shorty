<?php

namespace CannyDain\Sandbox\DependencyInjection\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Sandbox\DependencyInjection\Dependencies\Object1;

interface Sand_Object1Consumer extends ConsumerInterface
{
    public function consumeObject1(Object1 $object);
}