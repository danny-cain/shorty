<?php

namespace CannyDain\ShortyModules\Users\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Shorty\Controllers\ShortyModuleController;
use CannyDain\ShortyModules\Users\UsersModule;
use CannyDain\ShortyModules\Users\Views\LoginView;

class UserController extends ShortyModuleController
{
    public function Dashboard()
    {

    }

    public function Login()
    {
        $view = new LoginView();
        $view->setLoginRoute(new Route(__CLASS__, 'Login'));
        $this->_dependencies->applyDependencies($view);

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'Dashboard')));
        }

        return $view;
    }

    protected function _getModuleClassname()
    {
        return UsersModule::USERS_MODULE_CLASS;
    }
}