<?php

namespace CannyDain\ShortyCoreModules\UserModule\Views;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\UserModule\Models\GroupModel;

class EditGroupView extends HTMLView implements FormHelperConsumer, RouterConsumer
{
    /**
     * @var FormHelper
     */
    protected $_formHelper;
    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var GroupModel
     */
    protected $_group;

    /**
     * @var Route
     */
    protected $_saveRoute;

    public function display()
    {
        echo '<h1>Create/Edit Group</h1>';

        $this->_formHelper->startForm($this->_router->getURI($this->_saveRoute));
            $this->_formHelper->editText('name', 'Name', $this->_group->getName(), 'The name of this group');
            $this->_formHelper->submitButton('Save');
        $this->_formHelper->endForm();
    }

    public function updateFromRequest(Request $request)
    {
        $this->_group->setName($request->getParameter('name'));
    }

    /**
     * @param \CannyDain\ShortyCoreModules\UserModule\Models\GroupModel $group
     */
    public function setGroup($group)
    {
        $this->_group = $group;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\UserModule\Models\GroupModel
     */
    public function getGroup()
    {
        return $this->_group;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $saveRoute
     */
    public function setSaveRoute($saveRoute)
    {
        $this->_saveRoute = $saveRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getSaveRoute()
    {
        return $this->_saveRoute;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }
}
