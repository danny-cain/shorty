<?php

namespace CannyDain\Lib\DependencyInjection\Exceptions;

class InvalidConsumerInterfaceException extends DependencyInjectionException
{
    protected $_interface;

    public function __construct($interface)
    {
        $this->_interface = $interface;
    }

}