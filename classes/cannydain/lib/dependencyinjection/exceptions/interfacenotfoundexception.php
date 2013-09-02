<?php

namespace CannyDain\Lib\DependencyInjection\Exceptions;

class InterfaceNotFoundException extends DependencyInjectionException
{
    protected $_interface = '';

    public function __construct($interface)
    {
        $this->_interface = $interface;
    }
}