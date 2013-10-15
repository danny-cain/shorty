<?php

namespace CannyDain\ShortyModules\ShortyBasket\Models;

use CannyDain\Shorty\Models\ShortyModel;

class BasketModel extends ShortyModel
{
    const BASKET_OBJECT_TYPE = __CLASS__;

    protected $_id = 0;
    protected $_billingAddress = 0;
    protected $_deliveryAddress = 0;

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
    }

    public function setBillingAddress($billingAddress)
    {
        $this->_billingAddress = $billingAddress;
    }

    public function getBillingAddress()
    {
        return $this->_billingAddress;
    }

    public function setDeliveryAddress($deliveryAddress)
    {
        $this->_deliveryAddress = $deliveryAddress;
    }

    public function getDeliveryAddress()
    {
        return $this->_deliveryAddress;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }
}