<?php

namespace CannyDain\Sandbox\DependencyInjection\Factories;

use CannyDain\Lib\DependencyInjection\Interfaces\DependencyFactoryInterface;
use CannyDain\Sandbox\DependencyInjection\Dependencies\Object1;

class Sand_Object1Factory implements DependencyFactoryInterface
{
    public function createInstance($consumerInterface)
    {
        static $counter = 0;

        $object = new Object1();
        $object->setName('Instance '.$counter);
        $object->setAge(18 + $counter);
        $counter ++;

        return $object;
    }
}