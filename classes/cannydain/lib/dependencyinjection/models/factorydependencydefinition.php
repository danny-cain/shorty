<?php

namespace CannyDain\Lib\DependencyInjection\Models;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\DependencyInjection\Interfaces\DependencyFactoryInterface;

class FactoryDependencyDefinition extends DependencyDefinition
{
    /**
     * @var DependencyFactoryInterface
     */
    protected $_factory;

    public function __construct($interface, DependencyFactoryInterface $factory)
    {
        parent::__construct($interface, null);
        $this->_factory = $factory;
    }

    public function getDependency()
    {
        return $this->_factory->createInstance($this->_interface);
    }
}