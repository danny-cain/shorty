<?php

namespace CannyDain\ShortyCoreModules\UserModule\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\JSONView;
use CannyDain\Shorty\Config\ShortyConfiguration;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\ShortyCoreModules\UserModule\DataAccess\UserModuleDataLayer;

class UserAdminAPIController extends ShortyController implements DependencyConsumer, RouterConsumer
{
    const USER_ADMIN_API_CONTROLLER_NAME = __CLASS__;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var RouterInterface
     */
    protected $_router;

    public function SearchGroups($query)
    {
        $ret = array();

        foreach ($this->datasource()->searchGroups($query) as $group)
        {
            $ret[] = array
            (
                'id' => $group->getId(),
                'name' => $group->getName(),
                'editURI' => $this->_router->getURI(new Route(UserAdminController::USER_ADMIN_CONTROLLER_NAME, 'EditGroup', array($group->getId()))),
            );
        }

        return new JSONView($ret);
    }

    public function SearchUsers($query)
    {
        $ret = array();

        foreach ($this->datasource()->searchUsers($query) as $user)
        {
            $ret[] = array
            (
                'id' => $user->getId(),
                'editURI' => $this->_router->getURI(new Route(UserAdminController::USER_ADMIN_CONTROLLER_NAME, 'EditUser', array($user->getId()))),
                'username' => $user->getUsername(),
            );
        }

        return new JSONView($ret);
    }

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return true;
    }

    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new UserModuleDataLayer();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }
}