<?php

use CannyDain\Shorty\InstanceManager\InstanceManager;
use CannyDain\Shorty\Modules\ModuleManager;

require dirname(__FILE__).'/initialise.php';

class RescanTypesMain implements \CannyDain\Shorty\Execution\AppMain, \CannyDain\Shorty\Consumers\InstanceManagerConsumer
{
    /**
     * @var InstanceManager
     */
    protected $_instanceManager;

    public function main()
    {
        echo "Rescanning for types....\r\n";
        $this->_instanceManager->rescanAll();
        echo "Done\r\n";
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeInstanceManager(InstanceManager $dependency)
    {
        $this->_instanceManager = $dependency;
    }
}

ShortyInit::main(new RescanTypesMain());