<?php

namespace CannyDain\ShortyCoreModules\Payment_Invoice\Models;

class InvoiceItemModel
{
    const MODEL_CLASS_NAME = __CLASS__;

    protected $_id = 0;
    protected $_invoiceID = 0;

    protected $_itemName = '';
    protected $_pricePerUnit = 0;
    protected $_qty = 0;
    protected $_taxRate = 0.175;

    public function setTaxRate($taxRate)
    {
        $this->_taxRate = $taxRate;
    }

    public function getTaxRate()
    {
        return $this->_taxRate;
    }

    public function getLineTotalInPence()
    {
        return $this->_pricePerUnit * $this->_qty;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setInvoiceID($invoiceID)
    {
        $this->_invoiceID = $invoiceID;
    }

    public function getInvoiceID()
    {
        return $this->_invoiceID;
    }

    public function setItemName($itemName)
    {
        $this->_itemName = $itemName;
    }

    public function getItemName()
    {
        return $this->_itemName;
    }

    public function setPricePerUnit($pricePerUnit)
    {
        $this->_pricePerUnit = $pricePerUnit;
    }

    public function getPricePerUnit()
    {
        return $this->_pricePerUnit;
    }

    public function setQty($qty)
    {
        $this->_qty = $qty;
    }

    public function getQty()
    {
        return $this->_qty;
    }
}