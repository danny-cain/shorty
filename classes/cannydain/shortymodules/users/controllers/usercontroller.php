<?php

namespace CannyDain\ShortyModules\Users\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Shorty\Controllers\ShortyModuleController;
use CannyDain\ShortyModules\Users\Models\User;
use CannyDain\ShortyModules\Users\UsersModule;
use CannyDain\ShortyModules\Users\Views\LoginView;
use CannyDain\ShortyModules\Users\Views\RegisterView;

class UserController extends ShortyModuleController
{
    public function Dashboard()
    {

    }

    public function Register()
    {
        $view = new RegisterView();
        $user = $this->_getModule()->getDatasource()->createUser();

        $view->setRegisterRoute(new Route(__CLASS__, 'Register'));
        $view->setUser($user);

        $this->_dependencies->applyDependencies($view);

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            $user->save();
            return new RedirectView($this->_router->getURI($this->_getModule()->getRegisterRedirectRoute()));
        }

        return $view;
    }

    public function Login()
    {
        $view = new LoginView();
        $view->setLoginRoute(new Route(__CLASS__, 'Login'));
        $this->_dependencies->applyDependencies($view);

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            $route = $this->_getModule()->getLoginRedirectRoute();
            if ($route == null)
                $route = new Route();

            return new RedirectView($this->_router->getURI($route));
        }

        return $view;
    }

    /**
     * @return UsersModule
     */
    protected function _getModule()
    {
        return parent::_getModule();
    }


    protected function _getModuleClassname()
    {
        return UsersModule::USERS_MODULE_CLASS;
    }
}