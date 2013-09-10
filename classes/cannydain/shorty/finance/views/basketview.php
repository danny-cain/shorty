<?php

namespace CannyDain\Shorty\Finance\Views;

use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Finance\Models\PaymentProvider;
use CannyDain\Shorty\Views\ShortyView;

class BasketView extends ShortyView
{
    protected $_invoiceID;

    /**
     * @var ViewInterface
     */
    protected $_invoiceView;

    protected $_continueShoppingRoute = null;

    /**
     * @var PaymentProvider[]
     */
    protected $_paymentProviders = array();

    public function display()
    {
        echo '<h1>Basket</h1>';

        if ($this->_invoiceView != null)
            $this->_invoiceView->display();

        foreach ($this->_paymentProviders as $provider)
            $this->_displayPaymentProvider($provider);
    }

    protected function _displayPaymentProvider(PaymentProvider $provider)
    {
        $route = $provider->getCheckoutRoute();
        $uri = $this->_router->getURI($route);

        echo '<a class="checkoutButton" href="'.$uri.'">';
            echo $provider->getCheckoutButtonMarkup();
        echo '</a>';
    }

    public function setContinueShoppingRoute($continueShoppingRoute)
    {
        $this->_continueShoppingRoute = $continueShoppingRoute;
    }

    public function getContinueShoppingRoute()
    {
        return $this->_continueShoppingRoute;
    }

    public function setInvoiceID($invoiceID)
    {
        $this->_invoiceID = $invoiceID;
    }

    public function getInvoiceID()
    {
        return $this->_invoiceID;
    }

    /**
     * @param \CannyDain\Lib\UI\Views\ViewInterface $invoiceView
     */
    public function setInvoiceView($invoiceView)
    {
        $this->_invoiceView = $invoiceView;
    }

    /**
     * @return \CannyDain\Lib\UI\Views\ViewInterface
     */
    public function getInvoiceView()
    {
        return $this->_invoiceView;
    }

    public function setPaymentProviders($paymentProviders)
    {
        $this->_paymentProviders = $paymentProviders;
    }

    public function getPaymentProviders()
    {
        return $this->_paymentProviders;
    }
}