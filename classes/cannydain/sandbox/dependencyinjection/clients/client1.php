<?php

namespace CannyDain\Sandbox\DependencyInjection\Clients;

use CannyDain\Sandbox\DependencyInjection\Consumers\Sand_Object1Consumer;
use CannyDain\Sandbox\DependencyInjection\Dependencies\Object1;

class Client1 implements Sand_Object1Consumer
{
    public function dependenciesConsumed()
    {
        echo 'consumed<br>';
    }

    public function consumeObject1(Object1 $object)
    {
        echo 'consumed '.$object->getName().' ('.$object->getAge().')<br>';
    }
}