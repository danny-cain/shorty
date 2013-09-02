<?php

namespace CannyDain\Shorty\Bootstrap;

use CannyDain\Lib\DataMapping\DataMapperInterface;
use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Config\ShortyConfiguration;
use CannyDain\Shorty\Events\EventManager;
use CannyDain\Shorty\Modules\ModuleManager;

class BaseBootstrap
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var ShortyConfiguration
     */
    protected $_config;

    protected function _dependencyFactory()
    {
        return new BaseDependencyFactory();
    }

    /**
     * @param array $dependentObjects an array of objects to apply dependencies to
     */
    public function executeBootstrap($dependentObjects = array())
    {
        $this->_dependencies = new DependencyInjector();
        $dependencyFactory = $this->_dependencyFactory();

        $dependencyFactory->setConfig($this->_config);
        $dependencyFactory->setDependencyInjector($this->_dependencies);

        foreach ($dependencyFactory->getInterfaces() as $consumerInterface)
            $this->_dependencies->defineDependencyFactory($consumerInterface, $dependencyFactory);

        /**
         * @var EventManager $events
         */
        $events = $dependencyFactory->createInstance(BaseDependencyFactory::CONSUMER_EVENTS);

        /**
         * @var DataMapperInterface $datamapper
         */
        $datamapper = $dependencyFactory->createInstance(BaseDependencyFactory::CONSUMER_DATA_MAPPER);

        /**
         * @var ModuleManager $modules
         */
        $modules = $dependencyFactory->createInstance(BaseDependencyFactory::CONSUMER_MODULES);

        $this->_registerDataObjects($events, $datamapper);
        $this->_checkDatabase($datamapper);
        $this->_bootstrapComplete($events);

        foreach ($dependentObjects as $obj)
            $this->_dependencies->applyDependencies($obj);
    }

    protected function _registerDataObjects(EventManager $events, DataMapperInterface $datamapper)
    {
        $events->triggerEvent(EventManager::SHORTY_EVENT_REGISTER_DATA, array($datamapper));
    }

    protected function _checkDatabase(DataMapperInterface $datamapper)
    {
        $datamapper->dataStructureCheck();
    }

    protected function _bootstrapComplete(EventManager $events)
    {
        $events->triggerEvent(EventManager::SHORTY_EVENT_BOOTSTRAP_COMPLETE);
    }

    /**
     * @param \CannyDain\Shorty\Config\ShortyConfiguration $config
     */
    public function setConfig($config)
    {
        $this->_config = $config;
    }

    /**
     * @return \CannyDain\Shorty\Config\ShortyConfiguration
     */
    public function getConfig()
    {
        return $this->_config;
    }
}