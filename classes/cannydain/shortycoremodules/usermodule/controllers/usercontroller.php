<?php

namespace CannyDain\ShortyCoreModules\UserModule\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\ViewFactory;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Consumers\UserControlConsumer;
use CannyDain\Shorty\Consumers\ViewFactoryConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\Execution\ShortyExecutor;
use CannyDain\Shorty\UserControl\UserControl;
use CannyDain\ShortyCoreModules\UserModule\DataAccess\UserModuleDataLayer;
use CannyDain\ShortyCoreModules\UserModule\Models\UserModel;
use CannyDain\ShortyCoreModules\UserModule\Views\LoginView;
use CannyDain\ShortyCoreModules\UserModule\Views\RegisterUserView;

class UserController extends ShortyController implements RequestConsumer, DependencyConsumer, UserControlConsumer, RouterConsumer, ViewFactoryConsumer
{
    const USER_CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var ViewFactory
     */
    protected $_viewFactory;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var UserControl
     */
    protected $_userControl;

    /**
     * @var RouterInterface
     */
    protected $_router;

    protected function _registerViewFactory()
    {
        /**
         * @var RegisterUserView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\UserModule\\Views\\RegisterUserView');

        $this->_dependencies->applyDependencies($view);

        $view->setErrors(array());
        $view->setUser(new UserModel());
        $view->setRegisterRoute(new Route(__CLASS__, 'Register'));

        return $view;
    }

    protected function _loginViewFactory()
    {
        /**
         * @var LoginView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\UserModule\\Views\\LoginView');

        $this->_dependencies->applyDependencies($view);

        $view->setLoginURI($this->_router->getURI(new Route(__CLASS__, 'Login')));

        return $view;
    }

    public function Logout()
    {
        $this->_userControl->setCurrentUserID(0);
        return new RedirectView("/");
    }

    public function Register()
    {
        $view = $this->_registerViewFactory();

        if ($this->_request->isPost())
        {
            $view->updateFromRequest($this->_request);
            $existingUserID = $this->_userControl->getUserIDFromUsername($view->getUser()->getUsername());
            if ($existingUserID == null && $view->getUser()->getUsername() != '')
            {
                $this->datasource()->saveUser($view->getUser());
                $this->_userControl->setCurrentUserID($view->getUser()->getId());

                return new RedirectView("/");
            }
        }

        return $view;
    }

    public function Login()
    {
        $view = $this->_loginViewFactory();

        if ($this->_request->isPost())
        {
            $user = $this->_request->getParameter('username');
            $pass = $this->_request->getParameter('password');

            if ($this->_userControl->attemptLogin($user, $pass))
            {
                $view = new RedirectView();
                $this->_dependencies->applyDependencies($view);
                $view->setResponseCode(RedirectView::RESPONSE_CODE_TEMPORARY_REDIRECT);
                $view->setUri("/");
                return $view;
            }

            $view->setUsername($user);
            $view->setErrorMessage('Login Failed');
        }

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

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }

    public function consumeUserController(UserControl $dependency)
    {
        $this->_userControl = $dependency;
    }

    public function consumeViewFactory(ViewFactory $dependency)
    {
        $this->_viewFactory = $dependency;
    }

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return false;
    }
}