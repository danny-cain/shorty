<?php

namespace CannyDain\ShortyModules\Users\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\Helpers\Forms\Models\PasswordField;
use CannyDain\Shorty\Helpers\Forms\Models\SubmitButton;
use CannyDain\Shorty\Helpers\Forms\Models\TextboxField;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\Shorty\Views\ShortyFormView;
use CannyDain\ShortyModules\Users\Models\User;

class LoginView extends ShortyFormView implements SessionConsumer
{
    /**
     * @var SessionHelper
     */
    protected $_session;

    protected $_username = '';

    /**
     * @var Route
     */
    protected $_loginRoute;

    public function display()
    {
        $this->_setupForm();

        echo '<h1>Login</h1>';
        $this->_formHelper->displayForm();
    }

    /**
     * @return bool
     */
    public function updateFromPostAndReturnTrueIfPostedAndValid()
    {
        if (!$this->_request->isPost())
            return false;

        $this->_setupForm();
        $this->_formHelper->updateFromRequest($this->_request);
        $this->_username = $this->_formHelper->getField(User::FIELD_USERNAME)->getValue();
        $pass = $this->_formHelper->getField(User::FIELD_PASSWORD)->getValue();

        if ($this->_session->attemptLogin($this->_username, $pass))
            return true;

        $this->_formHelper->getField(User::FIELD_USERNAME)->setErrorText('Invalid username or password');
        return false;
    }

    protected function _setupForm()
    {
        if ($this->_formHelper->getField(User::FIELD_USERNAME) != null)
            return;

        $this->_formHelper->setMethod("POST")
                          ->setURI($this->_router->getURI($this->_loginRoute))
                          ->addField(new TextboxField('Username', User::FIELD_USERNAME, $this->_username, ''))
                          ->addField(new PasswordField('Password', User::FIELD_PASSWORD))
                          ->addField(new SubmitButton('Login'));
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $loginRoute
     */
    public function setLoginRoute($loginRoute)
    {
        $this->_loginRoute = $loginRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getLoginRoute()
    {
        return $this->_loginRoute;
    }

    public function setUsername($username)
    {
        $this->_username = $username;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }
}