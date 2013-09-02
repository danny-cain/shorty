<?php

namespace CannyDain\Shorty\Views;

use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Helpers\Forms\FormHelper;
use CannyDain\Shorty\Helpers\Forms\FormHelperInterface;

abstract class ShortyFormView extends ShortyView implements RequestConsumer, FormHelperConsumer
{
    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var FormHelperInterface
     */
    protected $_formHelper;

    /**
     * @return bool
     */
    public abstract function updateFromPostAndReturnTrueIfPostedAndValid();
    protected abstract function _setupForm();

    public function initialise()
    {
        $this->_setupForm();
    }

    public function consumeRequest(Request $request)
    {
        $this->_request = $request;
    }

    public function consumeFormHelper(FormHelperInterface $helper)
    {
        $this->_formHelper = $helper;
    }
}