<?php

namespace CannyDain\ShortyCoreModules\UserModule\Sidebars;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Consumers\UserControlConsumer;
use CannyDain\Shorty\Sidebars\SidebarInstances\HTMLSidebar;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\Shorty\UserControl\UserControl;
use CannyDain\ShortyCoreModules\UserModule\Controllers\UserController;

class UserSidebar extends HTMLSidebar implements UserControlConsumer, RouterConsumer, FormHelperConsumer
{
    /**
     * @var UserControl
     */
    protected $_userManager;

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    /**
     * @var RouterInterface
     */
    protected $_router;

    public function __construct()
    {

    }

    protected function _getNotLoggedInContent()
    {
        $loginURI = $this->_router->getURI(new Route(UserController::USER_CONTROLLER_CLASS_NAME, 'Login'));
        $registerURI = $this->_router->getURI(new Route(UserController::USER_CONTROLLER_CLASS_NAME, 'Register'));

        ob_start();
            $this->_formHelper->startForm($loginURI);
                $this->_formHelper->editText('username', 'Username', '');
                $this->_formHelper->editPassword('password', 'Password');
                $this->_formHelper->submitButton('Login');
            $this->_formHelper->endForm();

            echo 'or <a href="'.$registerURI.'">click here</a> to register';
        return ob_get_clean();
    }

    protected function _getLoggedInContent()
    {
        $username = $this->_userManager->getUsernameFromID($this->_userManager->getCurrentUserID());
        $sid = $this->_userManager->getCurrentSessionID();
        $logoutURI = $this->_router->getURI(new Route(UserController::USER_CONTROLLER_CLASS_NAME, 'Logout'));

        ob_start();

        echo '<p>You are logged in as '.$username.'</p>';
        echo '<p><a href="'.$logoutURI.'">Click Here</a> to log out</p>';

        return ob_get_clean();
    }

    protected function _getBookmarksForUser($userID)
    {
        $booksmarks = array();

        if ($this->_userManager->isAdministrator($userID))
        {
            $booksmarks['Shorty Project'] = '/cannydain-shortycoremodules-projectmanagement-controllers-projectmanagementcontroller/viewstories/1';
            $booksmarks['Shorty Project- In Progress'] = '/cannydain-shortycoremodules-projectmanagement-controllers-projectmanagementcontroller/search?project=1&status[]=2&status[]=3&status[]=4&status[]=5&status[]=6&query=';
            $booksmarks['Administration'] = '/cannydain-shortycoremodules-adminmodule-controllers-admincontroller';
        }

        return $booksmarks;
    }

    public function dependenciesConsumed()
    {
        $title = '';
        $content = '';

        if ($this->_userManager->getCurrentUserID() > 0)
        {
            $title = 'Account Control';
            $content = $this->_getLoggedInContent();
        }
        else
        {
            $title = 'Login';
            $content = $this->_getNotLoggedInContent();
        }
        $this->setTitle($title);
        $this->setContent($content);
    }

    public function consumeUserController(UserControl $dependency)
    {
        $this->_userManager = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}