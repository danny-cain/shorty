<?php

namespace CannyDain\Shorty\Finance\Models;

use CannyDain\Lib\Routing\Models\Route;

class PaymentProvider
{
    protected $_name = '';

    /**
     * @var Route
     */
    protected $_checkoutRoute = null;

    protected $_checkoutButtonMarkup = '';

    function __construct($_name, Route $_checkoutRoute, $_checkoutButtonMarkup)
    {
        $this->_checkoutButtonMarkup = $_checkoutButtonMarkup;
        $this->_checkoutRoute = $_checkoutRoute;
        $this->_name = $_name;
    }

    public function setCheckoutButtonMarkup($checkoutButtonMarkup)
    {
        $this->_checkoutButtonMarkup = $checkoutButtonMarkup;
    }

    public function getCheckoutButtonMarkup()
    {
        return $this->_checkoutButtonMarkup;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $checkoutRoute
     */
    public function setCheckoutRoute($checkoutRoute)
    {
        $this->_checkoutRoute = $checkoutRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getCheckoutRoute()
    {
        return $this->_checkoutRoute;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }
}