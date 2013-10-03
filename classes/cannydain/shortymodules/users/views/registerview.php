<?php

namespace CannyDain\ShortyModules\Users\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Helpers\Forms\Models\PasswordField;
use CannyDain\Shorty\Helpers\Forms\Models\SubmitButton;
use CannyDain\Shorty\Helpers\Forms\Models\TextboxField;
use CannyDain\Shorty\Views\ShortyFormView;
use CannyDain\ShortyModules\Users\Models\User;

class RegisterView extends ShortyFormView
{
    /**
     * @var User
     */
    protected $_user;
    /**
     * @var Route
     */
    protected $_registerRoute;

    const FIELD_CONFIRM_PASSWORD = 'confirm-password';

    protected function _setupForm()
    {
        if ($this->_formHelper->getField(User::FIELD_USERNAME) != null)
            return;

        $this->_formHelper->setMethod("POST");
        $this->_formHelper->setURI($this->_router->getURI($this->_registerRoute));
        $this->_formHelper->addField(new TextboxField('Email', User::FIELD_EMAIL, $this->_user->getEmail(), 'Your email address'));
        $this->_formHelper->addField(new TextboxField('Username', User::FIELD_USERNAME, $this->_user->getUsername(), 'Your username for this site (will be used for logins)'));
        $this->_formHelper->addField(new PasswordField('Password', User::FIELD_PASSWORD, 'Your password - we recommend using a mixture of uppercase and lowercase characters and symbols'));
        $this->_formHelper->addField(new PasswordField('Confirm Password', self::FIELD_CONFIRM_PASSWORD, 'Your password again, to make sure it wasn\'t mistyped'));
        $this->_formHelper->addField(new SubmitButton('Register'));
    }

    /**
     * @return bool
     */
    public function updateFromPostAndReturnTrueIfPostedAndValid()
    {
        if (!$this->_request->isPost())
            return false;

        $this->_user->setEmail($this->_request->getParameter(User::FIELD_EMAIL));
        $this->_user->setUsername($this->_request->getParameter(User::FIELD_USERNAME));

        $pass = $this->_request->getParameter(User::FIELD_PASSWORD);
        $confirmPass = $this->_request->getParameter(self::FIELD_CONFIRM_PASSWORD);

        $errors = array();
        if ($pass != $confirmPass)
        {
            $errors[self::FIELD_CONFIRM_PASSWORD] = 'Passwords do not match';
        }
        else
            $this->_user->changePassword($this->_request->getParameter(User::FIELD_PASSWORD));

        $this->_setupForm();

        $errors = array_merge($errors, $this->_user->validateAndReturnErrors());
        foreach ($errors as $field => $error)
            $this->_formHelper->getField($field)->setErrorText($error);

        return count($errors) == 0;
    }

    public function display()
    {
        $this->_setupForm();
        echo '<h1>Register</h1>';

        $this->_formHelper->displayForm();
    }

    /**
     * @param \CannyDain\ShortyModules\Users\Models\User $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }

    /**
     * @return \CannyDain\ShortyModules\Users\Models\User
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $registerRoute
     */
    public function setRegisterRoute($registerRoute)
    {
        $this->_registerRoute = $registerRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getRegisterRoute()
    {
        return $this->_registerRoute;
    }
}