<?php

namespace CannyDain\ShortyCoreModules\UserModule\Views;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\UserModule\Models\UserModel;

class EditUserView extends HTMLView implements FormHelperConsumer
{
    /**
     * @var UserModel
     */
    protected $_user;

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    /**
     * @var string
     */
    protected $_saveURI = '';

    public function display()
    {
        echo '<h1>Create/Edit User</h1>';

        $this->_formHelper->startForm($this->_saveURI);
            $this->_formHelper->editText('username', 'Username', $this->_user->getUsername(), 'The username they use to login');
            $this->_formHelper->editPassword('password', 'Change Password', 'Enter a new password for the user (if they require one)');
            $this->_formHelper->editCheckbox('admin', 'Is Administrator?', '1', $this->_user->getIsAdmin(), 'Tick this if the user is a site administrator');

            $this->_formHelper->submitButton('Save');
        $this->_formHelper->endForm();
    }

    public function updateModelFromRequest(Request $request)
    {
        $this->_user->setUsername($request->getParameter('username'));
        if ($request->getParameter('password') != '')
            $this->_user->changePassword($request->getParameter('password'));
        $this->_user->setIsAdmin($request->getParameter('admin') == '1');
    }

    /**
     * @param string $saveURI
     */
    public function setSaveURI($saveURI)
    {
        $this->_saveURI = $saveURI;
    }

    /**
     * @return string
     */
    public function getSaveURI()
    {
        return $this->_saveURI;
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

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}