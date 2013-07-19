<?php

namespace CannyDain\Shorty\ECommerce\Managers;

use CannyDain\Shorty\ECommerce\Models\ShortyBasketModel;

class ShippingManager
{
    protected $_shippingCharge = 0;

    public function __construct($shippingCharge = 0)
    {
        $this->_shippingCharge = $shippingCharge;
    }

    public function setShippingCharge($shippingCharge)
    {
        $this->_shippingCharge = $shippingCharge;
    }

    public function getShippingCharge()
    {
        return $this->_shippingCharge;
    }

    public function getTotalShippingInPenceForBasket(ShortyBasketModel $basket)
    {
        return $this->_shippingCharge;
    }
}