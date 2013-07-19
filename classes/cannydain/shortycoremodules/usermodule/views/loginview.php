<?php

namespace CannyDain\ShortyCoreModules\UserModule\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\Consumers\UserControlConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\Shorty\UserControl\UserControl;

class LoginView extends HTMLView implements UserControlConsumer, FormHelperConsumer
{
    protected $_errorMessage = '';
    protected $_username = '';
    protected $_loginURI = '';

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    /**
     * @var UserControl
     */
    protected $_userControl;

    public function display()
    {
        echo '<div style="width: 75%;">';
            $this->_formHelper->startForm($this->_loginURI);
                $this->_formHelper->editText('username', 'Username', $this->_username, 'Your username');
                $this->_formHelper->editPassword('password', 'Password', 'Your password');
                $this->_formHelper->submitButton('Login');
            $this->_formHelper->endForm();
        echo '</div>';
    }

    public function setLoginURI($loginURI)
    {
        $this->_loginURI = $loginURI;
    }

    public function getLoginURI()
    {
        return $this->_loginURI;
    }

    public function setErrorMessage($errorMessage)
    {
        $this->_errorMessage = $errorMessage;
    }

    public function getErrorMessage()
    {
        return $this->_errorMessage;
    }

    public function setUsername($username)
    {
        $this->_username = $username;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeUserController(UserControl $dependency)
    {
        $this->_userControl = $dependency;
    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}