<?php

namespace CannyDain\ShortyCoreModules\SimpleShop\Models;

use CannyDain\Shorty\ECommerce\Models\ShortyProductModel;

class SimpleShopProduct
{
    protected $_id = 0;
    protected $_name = '';
    protected $_summary = '';
    protected $_stockLevel = ShortyProductModel::STOCK_LEVEL_INFINITE;
    protected $_weight = 0;
    protected $_price = 0;
    protected $_taxRate = 0;

    public function setTaxRate($taxRate)
    {
        $this->_taxRate = $taxRate;
    }

    public function getTaxRate()
    {
        return $this->_taxRate;
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

    public function setPrice($price)
    {
        $this->_price = $price;
    }

    public function getPrice()
    {
        return $this->_price;
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

    public function setWeight($weight)
    {
        $this->_weight = $weight;
    }

    public function getWeight()
    {
        return $this->_weight;
    }
}