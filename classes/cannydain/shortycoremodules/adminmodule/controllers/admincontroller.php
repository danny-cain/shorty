<?php

namespace CannyDain\ShortyCoreModules\AdminModule\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;
use CannyDain\Shorty\Modules\Models\ModuleStatus;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyCoreModules\AdminModule\Views\AdminView;

class AdminController extends ShortyController implements ModuleConsumer, DependencyConsumer
{
    const CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var ModuleManager
     */
    protected $_moduleManager;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return true;
    }

    public function Index()
    {
        /**
         * @var ModuleInterface[]
         */
        $modules = array();

        foreach ($this->_moduleManager->getAllModuleStatuses() as $moduleStatus)
        {
            if ($moduleStatus->getStatus() != ModuleStatus::STATUS_ENABLED)
                continue;

            if (!class_exists($moduleStatus->getModuleName()))
                continue;

            $classname = $moduleStatus->getModuleName();
            $instance = new $classname();

            $modules[] = $instance;
        }

        $view = new AdminView();
        $this->_dependencies->applyDependencies($view);
        $view->setModules($modules);

        return $view;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeModuleManager(ModuleManager $dependency)
    {
        $this->_moduleManager = $dependency;
    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }
}