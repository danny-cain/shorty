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
use CannyDain\ShortyCoreModules\AdminModule\DataAccess\AdminModuleDataAccess;
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

        // todo - refactor this so it is in the view
        foreach ($this->datasource()->getAllDashboardEntries() as $entry)
        {
            $status = $this->_moduleManager->getModuleStatusByModuleName($entry->getModuleName());
            if ($status == null)
                continue;

            if ($status->getStatus() != ModuleStatus::STATUS_ENABLED)
                continue;

            $instance = $this->_moduleManager->getModuleInstanceByModuleName($entry->getModuleName());
            $modules[] = $instance;
        }

        $view = new AdminView();
        $this->_dependencies->applyDependencies($view);
        $view->setModules($modules);

        return $view;
    }

    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new AdminModuleDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
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