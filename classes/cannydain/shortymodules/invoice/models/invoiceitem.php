<?php

namespace CannyDain\ShortyModules\Invoice\Models;

use CannyDain\Shorty\Finance\Interfaces\InvoiceItemInterface;
use CannyDain\Shorty\Models\ShortyModel;

class InvoiceItem extends ShortyModel implements InvoiceItemInterface
{
    const OBJECT_TYPE_INVOICE_ITEM = __CLASS__;

    protected $_id = 0;
    protected $_status = self::STATUS_TO_BE_PROCESSED;
    protected $_lineDiscountInPence = 0;
    protected $_taxRate = 0;
    protected $_name = '';
    protected $_pricePerUnitInPence = 0;
    protected $_quantity = 0;
    protected $_invoiceID = 0;

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
    }

    public function getLineTotal()
    {
        return $this->getLineTotalWithoutDiscount() - $this->getLineDiscountInPence();
    }

    public function getLineTotalWithoutDiscount()
    {
        return ($this->getQuantity() * $this->getPricePerUnitInPence());
    }

    public function setInvoiceID($invoiceID)
    {
        $this->_invoiceID = $invoiceID;
    }

    public function getInvoiceID()
    {
        return $this->_invoiceID;
    }

    public function setLineDiscountInPence($lineDiscountInPence)
    {
        $this->_lineDiscountInPence = $lineDiscountInPence;
    }

    public function getLineDiscountInPence()
    {
        return $this->_lineDiscountInPence;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setPricePerUnitInPence($pricePerUnitInPence)
    {
        $this->_pricePerUnitInPence = $pricePerUnitInPence;
    }

    public function getPricePerUnitInPence()
    {
        return $this->_pricePerUnitInPence;
    }

    public function setQuantity($quantity)
    {
        $this->_quantity = $quantity;
    }

    public function getQuantity()
    {
        return $this->_quantity;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setTaxRate($taxRate)
    {
        $this->_taxRate = $taxRate;
    }

    public function getTaxRate()
    {
        return $this->_taxRate;
    }
}