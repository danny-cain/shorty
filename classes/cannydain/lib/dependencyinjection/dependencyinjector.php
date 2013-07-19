<?php

namespace CannyDain\Lib\DependencyInjection;

use CannyDain\Lib\DependencyInjection\Exceptions\InterfaceNotFoundException;
use CannyDain\Lib\DependencyInjection\Exceptions\InvalidConsumerInterfaceException;
use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Lib\DependencyInjection\Interfaces\DependencyFactoryInterface;
use CannyDain\Lib\DependencyInjection\Models\DependencyDefinition;
use CannyDain\Lib\DependencyInjection\Models\FactoryDependencyDefinition;
use CannyDain\ShortyCoreModules\ProjectManagement\Views\EditProjectView;

class DependencyInjector
{
    /**
     * @var DependencyDefinition[]
     */
    protected $_dependencies;

    public function defineDependency($interface, $dependency)
    {
        $this->_validateConsumerInterface($interface);
        $this->_dependencies[] = new DependencyDefinition($interface, $dependency);
    }

    public function defineDependencyFactory($interface, DependencyFactoryInterface $factory)
    {
        $this->_validateConsumerInterface($interface);
        $this->_dependencies[] = new FactoryDependencyDefinition($interface, $factory);
    }

    protected function _validateConsumerInterface($interface)
    {
        if (!interface_exists($interface))
            throw new InterfaceNotFoundException($interface);

        $reflect = new \ReflectionClass($interface);
        if (!$reflect->isSubclassOf('\CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface'))
            throw new InvalidConsumerInterfaceException($interface);
    }

    public function applyDependencies($object)
    {
        foreach ($this->_dependencies as $dependency)
            $this->_applyDependency($dependency, $object);

        if ($object instanceof ConsumerInterface)
            $object->dependenciesConsumed();
    }

    protected function _applyDependency(DependencyDefinition $dependency, $object)
    {
        $reflectObject = new \ReflectionObject($object);
        $interface = new \ReflectionClass($dependency->getInterface());

        if (!$reflectObject->implementsInterface($dependency->getInterface()))
            return;

        foreach ($interface->getMethods() as $method)
        {
            if (substr($method->getName(), 0, 7) != 'consume')
                continue;

            if ($method->getNumberOfRequiredParameters() > 1)
                continue;

            if ($method->getNumberOfParameters() == 0)
                continue;

            $actualMethod = $reflectObject->getMethod($method->getName());

            $actualMethod->invoke($object, $dependency->getDependency());
            return;
        }
    }
}