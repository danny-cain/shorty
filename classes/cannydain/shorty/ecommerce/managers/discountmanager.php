<?php

namespace CannyDain\Shorty\ECommerce\Managers;

use CannyDain\Shorty\ECommerce\Models\ShortyBasketModel;

class DiscountManager
{
    protected $_discounts = array();

    public function __construct($discounts = array())
    {
        $this->_discounts = $discounts;
    }

    public function getTotalDiscountInPenceForBasket(ShortyBasketModel $basket)
    {
        if (isset($this->_discounts[$basket->getDiscountCode()]))
            return $this->_discounts[$basket->getDiscountCode()];

        return 0;
    }
}