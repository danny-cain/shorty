<?php

namespace CannyDain\Shorty\Controllers;

use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Exceptions\InvalidStateException;
use CannyDain\Shorty\Modules\ModuleManager;

abstract class ShortyModuleController extends ShortyController implements ModuleConsumer
{
    /**
     * @var ModuleManager
     */
    protected $_modules;

    protected abstract function _getModuleClassname();

    public function _validateState()
    {
        parent::_validateState();
        $this->_stateValidator_ModuleIsAvailable();
    }


    protected function _stateValidator_ModuleIsAvailable()
    {
        if ($this->_getModule() == null)
            throw new InvalidStateException(__CLASS__, 'Module not available');
    }

    protected function _getModule()
    {
        return $this->_modules->getModuleByClassname($this->_getModuleClassname());
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        $this->_modules = $manager;
    }
}