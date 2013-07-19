<?php

namespace CannyDain\Shorty\Execution;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Exceptions\ControllerNotFoundException;
use CannyDain\Lib\Execution\Exceptions\NotAuthorisedException;
use CannyDain\Lib\Execution\Executors\BasicExecutor;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Shorty\UserControl\UserControl;

class ShortyExecutor extends BasicExecutor
{
    /**
     * @var UserControl
     */
    protected $_userManager;

    public function __construct(DependencyInjector $dependencyInjector = null, UserControl $userControl)
    {
        parent::__construct($dependencyInjector);
        $this->_userManager = $userControl;
    }

    protected function _checkAccessRightsForController(ControllerInterface $controller)
    {
        if (!$this->_userManager->isAdministrator($this->_userManager->getCurrentUserID()) && $controller->__isAdministratorOnly())
            throw new NotAuthorisedException();
    }

}