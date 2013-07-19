<?php

namespace CannyDain\Shorty\ECommerce\Models;

class ShortyBasketItemModel
{
    protected $_id = 0;
    protected $_basketID = 0;
    protected $_productGUID = '';
    protected $_name = '';
    protected $_priceInPencePerUnit = 0;
    protected $_taxRate = 0;
    protected $_weightPerUnit = 0;
    protected $_summary = '';
    protected $_qty = 0;

    public function setTaxRate($taxRate)
    {
        $this->_taxRate = $taxRate;
    }

    public function getTaxRate()
    {
        return $this->_taxRate;
    }

    public function setBasketID($basketID)
    {
        $this->_basketID = $basketID;
    }

    public function getBasketID()
    {
        return $this->_basketID;
    }

    public function setProductGUID($productGUID)
    {
        $this->_productGUID = $productGUID;
    }

    public function getProductGUID()
    {
        return $this->_productGUID;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setPriceInPencePerUnit($priceInPencePerUnit)
    {
        $this->_priceInPencePerUnit = $priceInPencePerUnit;
    }

    public function getPriceInPencePerUnit()
    {
        return $this->_priceInPencePerUnit;
    }

    public function setQty($qty)
    {
        $this->_qty = $qty;
    }

    public function getQty()
    {
        return $this->_qty;
    }

    public function setSummary($summary)
    {
        $this->_summary = $summary;
    }

    public function getSummary()
    {
        return $this->_summary;
    }

    public function setWeightPerUnit($weightPerUnit)
    {
        $this->_weightPerUnit = $weightPerUnit;
    }

    public function getWeightPerUnit()
    {
        return $this->_weightPerUnit;
    }
}