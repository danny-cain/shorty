<?php

namespace CannyDain\Shorty\Modules;

use CannyDain\Lib\DataMapping\DataMapperInterface;
use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\EventConsumer;
use CannyDain\Shorty\Events\EventManager;
use CannyDain\Shorty\Events\Events\BootstrapCompleteEvent;
use CannyDain\Shorty\Events\Events\RegisterDataEvent;
use CannyDain\Shorty\Modules\Base\ModuleInterface;

class ModuleManager implements EventConsumer, BootstrapCompleteEvent, RegisterDataEvent, DependencyConsumer
{
    /**
     * @var EventManager
     */
    protected $_eventManager;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var ModuleInterface[]
     */
    protected $_modules = array();

    /**
     * @param $classname
     * @return ModuleInterface|null
     */
    public function getModuleByClassname($classname)
    {
        foreach ($this->_modules as $module)
        {
            if (strtolower(get_class($module)) == strtolower($classname))
                return $module;
        }

        return null;
    }

    /**
     * @return ModuleInterface[]
     */
    public function getModules()
    {
        return array_values($this->_modules);
    }

    public function loadModule(ModuleInterface $module)
    {
        $this->_modules[] = $module;
    }

    public function consumeEventManager(EventManager $eventManager)
    {
        $eventManager->subscribeToEvents($this, array
        (
            EventManager::SHORTY_EVENT_BOOTSTRAP_COMPLETE,
            EventManager::SHORTY_EVENT_REGISTER_DATA,
        ));
    }

    public function consumeDependencies(DependencyInjector $dependencies)
    {
        $this->_dependencies = $dependencies;
    }

    public function _event_bootstrapComplete()
    {
        foreach ($this->_modules as $module)
            $module->initialise();
    }

    public function _event_registerData(DataMapperInterface $datamapper)
    {
        foreach ($this->_modules as $module)
        {
            $this->_dependencies->applyDependencies($module);
            $module->registerDataObjects();
        }
    }
}