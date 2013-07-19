<?php

namespace CannyDain\Shorty\InstanceManager;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Config\ShortyConfiguration;
use CannyDain\Shorty\Consumers\ConfigurationConsumer;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\InstanceManager\DataAccess\InstanceManagerDataAccess;
use CannyDain\Shorty\InstanceManager\Models\BaseTypeDefinition;
use CannyDain\Shorty\InstanceManager\Models\InstanceDefinition;
use CannyDain\Shorty\InstanceManager\Workers\ClassScanner;
use ReflectionClass;

class InstanceManager implements DependencyConsumer, ConfigurationConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var ShortyConfiguration
     */
    protected $_config;

    public function registerObjects()
    {
        $this->datasource()->registerObjects();
    }

    public function ensureTypeIsRegistered($interfaceOrBaseClassName, $friendlyName)
    {
        $existingType = $this->datasource()->getBaseTypeByInterfaceOrBaseClass($interfaceOrBaseClassName);
        if ($existingType != null)
            return;

        $baseType = new BaseTypeDefinition();
        $baseType->setFriendlyTypeName($friendlyName);
        $baseType->setInterfaceOrClassName($interfaceOrBaseClassName);
        $this->datasource()->saveBaseType($baseType);
    }

    public function rescanAll()
    {
        $types = $this->getTypes();
        $basePath = $this->_config->getValue(ShortyConfiguration::CONFIG_KEY_CLASSES_ROOT);
        $scanner = new ClassScanner();

        $classes = $scanner->scanForClasses($basePath);
        foreach ($classes as $class)
            $this->_processScannedClass($class, $types);
    }

    /**
     * @param $className
     * @param BaseTypeDefinition[] $types
     */
    protected function _processScannedClass($className, $types)
    {
        if (!class_exists($className))
        {
            die('could not find '.$className);
        }
        $reflection = new ReflectionClass($className);

        if ($reflection->isAbstract())
            return;

        foreach ($types as $type)
        {
            if (!$this->_doesClassMatchType($reflection, $type))
                continue;

            // register instance
            $existingInstance = $this->datasource()->getInstanceByBaseTypeAndClassName($type->getId(), $className);
            if ($existingInstance != null)
                continue;

            $instance = new InstanceDefinition();
            $instance->setBaseType($type->getId());
            $instance->setClassName($className);
            $this->datasource()->saveInstance($instance);
        }
    }

    protected function _doesClassMatchType(ReflectionClass $reflectionClass, BaseTypeDefinition $type)
    {
        if (interface_exists($type->getInterfaceOrClassName()))
            return $reflectionClass->implementsInterface($type->getInterfaceOrClassName());

        if (class_exists($type->getInterfaceOrClassName()))
            return $reflectionClass->isSubclassOf($type->getInterfaceOrClassName());

        return false;
    }

    /**
     * @param $id
     * @return \CannyDain\Shorty\InstanceManager\Models\InstanceDefinition
     */
    public function getInstanceByID($id)
    {
        return $this->datasource()->getInstanceByID($id);
    }

    /**
     * @param $typeID
     * @return InstanceDefinition[]
     */
    public function getInstancesByType($typeID)
    {
        return $this->datasource()->getInstancesByBaseType($typeID);
    }

    public function getTypeByInterfaceOrClassname($interfaceOrClassname)
    {
        return $this->datasource()->getBaseTypeByInterfaceOrBaseClass($interfaceOrClassname);
    }

    public function getTypes()
    {
        return $this->datasource()->getAllBaseTypes();
    }

    /**
     * @return InstanceManagerDataAccess
     */
    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new InstanceManagerDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function dependenciesConsumed()
    {
        // TODO: Implement dependenciesConsumed() method.
    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeConfiguration(ShortyConfiguration $dependency)
    {
        $this->_config = $dependency;
    }
}