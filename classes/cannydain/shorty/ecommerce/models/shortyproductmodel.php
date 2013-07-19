<?php

namespace CannyDain\Shorty\ECommerce\Models;

class ShortyProductModel
{
    const STOCK_LEVEL_INFINITE = -1;

    protected $_guid = '';
    protected $_name = '';
    protected $_priceInPencePerUnit = 0;
    protected $_stockLevel = self::STOCK_LEVEL_INFINITE;
    protected $_weightPerUnit = 0;
    protected $_summary = 0;
    protected $_taxRate = 0;

    public function setTaxRate($taxRate)
    {
        $this->_taxRate = $taxRate;
    }

    public function getTaxRate()
    {
        return $this->_taxRate;
    }

    public function setGuid($id)
    {
        $this->_guid = $id;
    }

    public function getGuid()
    {
        return $this->_guid;
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

    public function setStockLevel($stockLevel)
    {
        $this->_stockLevel = $stockLevel;
    }

    public function getStockLevel()
    {
        return $this->_stockLevel;
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