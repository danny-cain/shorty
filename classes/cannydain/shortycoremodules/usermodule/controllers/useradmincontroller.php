<?php

namespace CannyDain\ShortyCoreModules\UserModule\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\ShortyCoreModules\UserModule\DataAccess\UserModuleDataLayer;
use CannyDain\ShortyCoreModules\UserModule\Models\GroupModel;
use CannyDain\ShortyCoreModules\UserModule\Models\UserModel;
use CannyDain\ShortyCoreModules\UserModule\Views\EditGroupView;
use CannyDain\ShortyCoreModules\UserModule\Views\EditUserView;
use CannyDain\ShortyCoreModules\UserModule\Views\UserAdminIndexView;

class UserAdminController extends ShortyController implements RouterConsumer, DependencyConsumer, RequestConsumer
{
    const USER_ADMIN_CONTROLLER_NAME = __CLASS__;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;
    /**
     * @var Request
     */
    protected $_request;

    public function Index()
    {
        return $this->_view_Index();
    }

    public function EditGroup($groupID)
    {
        $view = $this->_view_EditGroup($this->datasource()->getGroupByID($groupID));

        if ($this->_request->isPost())
        {
            $view->updateFromRequest($this->_request);
            $this->datasource()->saveGroup($view->getGroup());

            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
        }

        return $view;
    }

    public function CreateGroup()
    {
        $view = $this->_view_EditGroup(new GroupModel());

        if ($this->_request->isPost())
        {
            $view->updateFromRequest($this->_request);
            $this->datasource()->saveGroup($view->getGroup());

            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
        }

        return $view;
    }

    public function EditUser($userID)
    {
        $user = $this->datasource()->getUserByID($userID);
        $view = $this->_view_EditUser($user);

        if ($this->_request->isPost())
        {
            $view->updateModelFromRequest($this->_request);
            $this->datasource()->saveUser($user);

            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
        }
        return $view;
    }

    public function CreateUser()
    {
        $user = new UserModel;
        $view = $this->_view_EditUser($user);

        if ($this->_request->isPost())
        {
            $view->updateModelFromRequest($this->_request);
            $this->datasource()->saveUser($user);

            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
        }

        return $view;
    }

    protected function _view_Index()
    {
        $view = new UserAdminIndexView();
        $view->setUserSearchAPIURI($this->_router->getURI(new Route(UserAdminAPIController::USER_ADMIN_API_CONTROLLER_NAME, 'SearchUsers', array('#query#'))));
        $view->setGroupSearchAPIURI($this->_router->getURI(new Route(UserAdminAPIController::USER_ADMIN_API_CONTROLLER_NAME, 'SearchGroups', array('#query#'))));
        $view->setCreateUserURI($this->_router->getURI(new Route(__CLASS__, 'CreateUser')));
        $view->setCreateGroupURITemplate($this->_router->getURI(new Route(__CLASS__, 'CreateGroup')));
        $view->setEditUserURITemplate($this->_router->getURI(new Route(__CLASS__, 'EditUser', array('#id#'))));
        $view->setRecentRegistrations($this->datasource()->getMostRecentRegistrations(5));

        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    protected function _view_EditGroup(GroupModel $group)
    {
        $view = new EditGroupView();
        $view->setGroup($group);

        $this->_dependencies->applyDependencies($view);

        if ($group->getId() > 0)
            $view->setSaveRoute(new Route(__CLASS__, 'EditGroup', array($group->getId())));
        else
            $view->setSaveRoute(new Route(__CLASS__, 'CreateGroup'));

        return $view;
    }

    protected function _view_EditUser(UserModel $user)
    {
        $view = new EditUserView();
        $view->setUser($user);
        if ($user->getId() == 0)
        {
            $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'CreateUser')));
        }
        else
        {
            $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'EditUser', array($user->getId()))));
        }

        $this->_dependencies->applyDependencies($view);

        return $view;
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

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return true;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }
}