<?php

namespace CannyDain\ShortyCoreModules\UserModule\Views;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\UserModule\Models\UserModel;

class RegisterUserView extends HTMLView implements RouterConsumer, FormHelperConsumer
{
    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var UserModel
     */
    protected $_user;

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    /**
     * @var Route
     */
    protected $_registerRoute;

    protected $_errors = array();

    public function display()
    {
        echo '<h1>Register an Account</h1>';

        $this->_formHelper->startForm($this->_router->getURI($this->_registerRoute));
            $this->_formHelper->antiBotHiddenField('office-use');
            $this->_formHelper->editText('username', 'Username', $this->_user->getUsername(), 'The username you wish to use');
            $this->_formHelper->editPassword('password', 'Password', 'The password you wish to use to login');
            $this->_formHelper->submitButton('Register an account');
        $this->_formHelper->endForm();
    }

    public function updateFromRequest(Request $request)
    {
        if ($request->getParameter('office-use') != '')
            return;

        $this->_user->setUsername($request->getParameter('username'));
        $this->_user->changePassword($request->getParameter('password'));
        $this->_user->setRegistrationDate(time());
    }

    public function setErrors($errors)
    {
        $this->_errors = $errors;
    }

    public function getErrors()
    {
        return $this->_errors;
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

    /**
     * @param \CannyDain\ShortyCoreModules\UserModule\Models\UserModel $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\UserModule\Models\UserModel
     */
    public function getUser()
    {
        return $this->_user;
    }

    public function dependenciesConsumed()
    {

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