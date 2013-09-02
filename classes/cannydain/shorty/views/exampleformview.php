<?php

namespace CannyDain\Shorty\Views;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Helpers\Forms\FormHelperInterface;
use CannyDain\Shorty\Helpers\Forms\Models\SingleSelectField;
use CannyDain\Shorty\Helpers\Forms\Models\SubmitButton;
use CannyDain\Shorty\Helpers\Forms\Models\TextboxField;

class ExampleFormView extends ShortyFormView implements FormHelperConsumer, RouterConsumer, RequestConsumer
{
    /**
     * @var FormHelperInterface
     */
    protected $_formHelper;

    /**
     * @var Route
     */
    protected $_route;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var RouterInterface
     */
    protected $_router;

    protected $_name = '';
    protected $_age = 0;

    /**
     * @return bool
     */
    public function updateFromPostAndReturnTrueIfPostedAndValid()
    {
        if (!$this->_request->isPost())
            return false;

        $this->_formHelper->updateFromRequest($this->_request);
        $this->_name = $this->_formHelper->getField('name')->getValue();
        $this->_age = $this->_formHelper->getField('age')->getValue();

        return true;
    }

    public function setAge($age)
    {
        $this->_age = $age;
    }

    public function getAge()
    {
        return $this->_age;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function display()
    {
        $this->_formHelper->displayForm();
    }

    protected function _setupForm()
    {
        $this->_formHelper->setMethod(FormHelperInterface::FORM_METHOD_POST)
                          ->setURI($this->_router->getURI($this->_route))
                          ->addField(new TextboxField('Name', 'name', $this->_name, 'Your name'))
                          ->addField(new SingleSelectField('Age', 'age', $this->_age, array
                            (
                                0 => '< 18',
                                1 => '18 - 24',
                                2 => '25 - 30',
                                3 => '30 +',
                            ), 'Your age'))
                          ->addField(new SubmitButton('Submit Form'));
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $route
     */
    public function setRoute($route)
    {
        $this->_route = $route;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getRoute()
    {
        return $this->_route;
    }

    public function consumeFormHelper(FormHelperInterface $helper)
    {
        if ($this->_formHelper != null)
            return;

        $this->_formHelper = $helper;
        $this->_setupForm();
    }

    public function consumeRouter(RouterInterface $router)
    {
        $this->_router = $router;
    }

    public function consumeRequest(Request $request)
    {
        $this->_request = $request;
    }
}