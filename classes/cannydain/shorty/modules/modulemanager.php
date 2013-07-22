<?php

namespace CannyDain\Shorty\Modules;

use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\InstanceManagerConsumer;
use CannyDain\Shorty\InstanceManager\InstanceManager;
use CannyDain\Shorty\Modules\DataAccess\ModulesDataLayer;
use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;
use CannyDain\Shorty\Modules\Models\ModuleStatus;

class ModuleManager implements DependencyConsumer, InstanceManagerConsumer, DataMapperConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var DataMapper
     */
    protected $_datamapper;

    /**
     * @var InstanceManager
     */
    protected $_instanceManager;

    /**
     * @var ModuleStatus[]
     */
    protected $_knownModules = array();

    /**
     * @var ModuleInterface[]
     */
    protected $_loadedModules = array();

    public function getModuleInstanceByModuleName($moduleName)
    {
        foreach ($this->_loadedModules as $module)
        {
            if ('\\'.get_class($module) == $moduleName)
                return $module;
        }

        return null;
    }

    public function getModuleStatusByModuleName($moduleName)
    {
        foreach ($this->_knownModules as $status)
        {
            if ($status->getModuleName() == $moduleName)
                return $status;
        }

        return null;
    }

    public function initialise()
    {
        $this->datasource()->registerObjects();

        $this->_knownModules = $this->datasource()->getAllKnownModules();

        foreach ($this->_knownModules as $moduleInfo)
        {
            if ($moduleInfo->getStatus() != ModuleStatus::STATUS_ENABLED)
                continue;

            $module = $this->_moduleFactory($moduleInfo);
            $module->initialise();
            $this->_loadedModules[$moduleInfo->getId()] = $module;
        }
    }

    public function installModule($id)
    {
        $module = $this->getModuleByID($id);

        if ($module->getStatus() != ModuleStatus::STATUS_UNINSTALLED)
            return;

        $moduleInstance = $this->_moduleFactory($module);

        $moduleInstance->initialise();
        $this->_datamapper->dataStructureCheck();

        $module->setStatus(ModuleStatus::STATUS_INSTALLED);
        $this->datasource()->saveModuleInfo($module);
    }

    public function enableModule($id)
    {
        $module = $this->getModuleByID($id);
        if ($module == null)
            return;

        if ($module->getStatus() == ModuleStatus::STATUS_UNINSTALLED)
            $this->installModule($id);

        $module->setStatus(ModuleStatus::STATUS_ENABLED);
        $this->datasource()->saveModuleInfo($module);

        $moduleInstance = $this->_moduleFactory($module);
        $moduleInstance->initialise();

        $moduleInstance->enable();

        $this->_loadedModules[] = $module;
    }

    public function disableModule($id)
    {
        if (!isset($this->_loadedModules[$id]))
            return;

        $moduleInstance = $this->_loadedModules[$id];
        $moduleInfo = $this->getModuleByID($id);

        $moduleInstance->disable();
        unset($this->_loadedModules[$id]);
        $moduleInfo->setStatus(ModuleStatus::STATUS_INSTALLED);
        $this->datasource()->saveModuleInfo($moduleInfo);
    }

    public function getModuleByID($id)
    {
        foreach ($this->_knownModules as $module)
        {
            if ($module->getId() == $id)
                return $module;
        }

        return null;
    }

    public function getAllModuleStatuses()
    {
        return $this->_knownModules;
    }

    public function scanForModules()
    {
        $this->_instanceManager->ensureTypeIsRegistered('\CannyDain\Shorty\Modules\Interfaces\ModuleInterface', 'Module');
        $this->_instanceManager->rescanAll();

        $modulesByClassname = array();
        foreach ($this->_knownModules as $module)
        {
            $modulesByClassname[$module->getModuleName()] = $module;
        }

        $type = $this->_instanceManager->getTypeByInterfaceOrClassname('\\CannyDain\\Shorty\\Modules\\Interfaces\\ModuleInterface');
        foreach ($this->_instanceManager->getInstancesByType($type->getId()) as $instance)
        {
            if (isset($modulesByClassname[$instance->getClassName()]))
                continue;


            $status = new ModuleStatus();
            $status->setModuleName($instance->getClassName());
            $status->setStatus(ModuleStatus::STATUS_UNINSTALLED);
            $this->datasource()->saveModuleInfo($status);
        }
    }

    /**
     * @param ModuleStatus $moduleInfo
     * @return ModuleInterface
     */
    protected function _moduleFactory(ModuleStatus $moduleInfo)
    {
        $class = $moduleInfo->getModuleName();
        $module = new $class();

        $this->_dependencies->applyDependencies($module);

        return $module;
    }

    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new ModulesDataLayer();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function consumeInstanceManager(InstanceManager $dependency)
    {
        $this->_instanceManager = $dependency;
    }

    public function dependenciesConsumed()
    {
        // TODO: Implement dependenciesConsumed() method.
    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }
}