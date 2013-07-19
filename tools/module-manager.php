<?php

use CannyDain\Shorty\Modules\ModuleManager;

require dirname(__FILE__).'/initialise.php';

class ModuleManagerMain implements \CannyDain\Shorty\Execution\AppMain, \CannyDain\Shorty\Consumers\ModuleConsumer
{
    /**
     * @var \CannyDain\Shorty\Modules\ModuleManager
     */
    protected $_moduleManager;

    protected $_modulesToInstall = array();
    protected $_modulesToDisable = array();
    protected $_modulesToEnable = array();
    protected $_scan = false;

    public function main()
    {
        $this->_setup();
        // execute

        if ($this->_scan)
            $this->_scan();

        foreach ($this->_modulesToInstall as $id)
            $this->_moduleManager->installModule($id);

        foreach ($this->_modulesToEnable as $id)
            $this->_moduleManager->enableModule($id);

        foreach ($this->_modulesToDisable as $id)
            $this->_moduleManager->disableModule($id);

        $this->_listModules();
    }

    protected function _setup()
    {
        $options = getopt('si:e:d:');
        if (isset($options['s']))
            $this->_scan = true;

        if (isset($options['i']))
            $this->_modulesToInstall = explode(',', $options['i']);

        if (isset($options['e']))
            $this->_modulesToEnable = explode(',', $options['e']);

        if (isset($options['d']))
            $this->_modulesToDisable = explode(',', $options['d']);
    }

    protected function _scan()
    {
        $this->_moduleManager->scanForModules();
    }

    protected function _listModules()
    {
        foreach ($this->_moduleManager->getAllModuleStatuses() as $module)
        {
            $sections = array
            (
                $module->getId(),
                $this->getStatusText($module),
                $module->getModuleName(),
            );

            echo implode("\t", $sections)."\r\n";
        }
    }

    protected function getStatusText(\CannyDain\Shorty\Modules\Models\ModuleStatus $module)
    {
        switch($module->getStatus())
        {
            case \CannyDain\Shorty\Modules\Models\ModuleStatus::STATUS_ENABLED:
                return 'e';
            case \CannyDain\Shorty\Modules\Models\ModuleStatus::STATUS_UNINSTALLED:
                return '-';
            case \CannyDain\Shorty\Modules\Models\ModuleStatus::STATUS_INSTALLED:
                return 'd';
        }
        return ' ';
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeModuleManager(ModuleManager $dependency)
    {
        $this->_moduleManager = $dependency;
    }
}

ShortyInit::main(new ModuleManagerMain());