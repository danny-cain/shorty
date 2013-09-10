<?php

namespace CannyDain\Shorty\ECommerce\Products\Models;

class ProductInfoModel
{
    const STOCK_LEVEL_INFINITE = -1;

    protected $_guid = '';
    protected $_pricePerUnitInPence = 0;
    protected $_taxRate = 0;
    protected $_description = '';
    protected $_name = '';
    protected $_stockLevel = self::STOCK_LEVEL_INFINITE;

    public function setGuid($guid)
    {
        $this->_guid = $guid;
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

    public function setPricePerUnitInPence($pricePerUnitInPence)
    {
        $this->_pricePerUnitInPence = $pricePerUnitInPence;
    }

    public function getPricePerUnitInPence()
    {
        return $this->_pricePerUnitInPence;
    }

    public function setStockLevel($stockLevel)
    {
        $this->_stockLevel = $stockLevel;
    }

    public function getStockLevel()
    {
        return $this->_stockLevel;
    }

    public function setTaxRate($taxRate)
    {
        $this->_taxRate = $taxRate;
    }

    public function getTaxRate()
    {
        return $this->_taxRate;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }

    public function getDescription()
    {
        return $this->_description;
    }
}