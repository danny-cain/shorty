<?php

namespace CannyDain\ShortyCoreModules\Payment_Invoice\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;

class CheckoutButtonView extends HTMLView implements FormHelperConsumer
{
    /**
     * @var FormHelper
     */
    protected $_formHelper;

    protected $_paymentURI = '';

    public function display()
    {
        echo '<form method="get" action="'.$this->_paymentURI.'">';
            echo '<input type="submit" value="Pay by Invoice" />';
        echo '</form>';
    }

    public function setPaymentURI($paymentURI)
    {
        $this->_paymentURI = $paymentURI;
    }

    public function getPaymentURI()
    {
        return $this->_paymentURI;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}