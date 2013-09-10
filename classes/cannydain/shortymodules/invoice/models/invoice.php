<?php

namespace CannyDain\ShortyModules\Invoice\Models;

use CannyDain\Shorty\Finance\Interfaces\InvoiceInterface;
use CannyDain\Shorty\Models\ShortyModel;

class Invoice extends ShortyModel implements InvoiceInterface
{
    const OBJECT_TYPE_INVOICE = __CLASS__;

    protected $_id = 0;
    protected $_status = self::STATUS_TO_BE_INVOICED;
    protected $_discountCode = '';
    protected $_deliveryAddress = 0;
    protected $_billingAddress = 0;

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

    public function setDiscountCode($discountCode)
    {
        $this->_discountCode = $discountCode;
    }

    public function getDiscountCode()
    {
        return $this->_discountCode;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function getStatus()
    {
        return $this->_status;
    }
}