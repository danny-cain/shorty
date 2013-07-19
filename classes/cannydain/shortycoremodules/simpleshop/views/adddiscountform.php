<?php

namespace CannyDain\ShortyCoreModules\SimpleShop\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;

class AddDiscountForm extends HTMLView implements FormHelperConsumer
{
    protected $_discountCode = '';

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    protected $_applyURI = '';

    public function display()
    {
        $this->_formHelper->startForm($this->_applyURI);
            $this->_formHelper->editText('discount', 'Discount Code', $this->_discountCode);
            $this->_formHelper->submitButton('Apply Discount');
        $this->_formHelper->endForm();
    }

    public function updateFromRequest(Request $request)
    {
        $this->_discountCode = $request->getParameter('discount');
    }

    public function setDiscountCode($discountCode)
    {
        $this->_discountCode = $discountCode;
    }

    public function getDiscountCode()
    {
        return $this->_discountCode;
    }

    public function setApplyURI($applyURI)
    {
        $this->_applyURI = $applyURI;
    }

    public function getApplyURI()
    {
        return $this->_applyURI;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}