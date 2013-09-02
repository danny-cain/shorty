<?php

namespace CannyDain\ShortyModules\SimpleShop\Models;

use CannyDain\Shorty\Models\ShortyGUIDModel;

class Product extends ShortyGUIDModel
{
    const OBJECT_TYPE_PRODUCT = __CLASS__;
    const STOCK_LEVEL_INFINITE = -1;

    const FIELD_NAME = 'name';
    const FIELD_SHORT_DESC = 'shortdesc';
    const FIELD_LONG_DESC = 'longdesc';
    const FIELD_PRICE = 'price';
    const FIELD_IMAGE = 'image';
    const FIELD_STOCK = 'stock';

    protected $_name = '';
    protected $_shortDescription = '';
    protected $_longDescription = '';
    protected $_priceInPence = 0;
    protected $_image = '';
    protected $_stockLevel = self::STOCK_LEVEL_INFINITE;

    public function setImage($image)
    {
        $this->_image = $image;
    }

    public function getImage()
    {
        return $this->_image;
    }

    public function setLongDescription($longDescription)
    {
        $this->_longDescription = $longDescription;
    }

    public function getLongDescription()
    {
        return $this->_longDescription;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setPriceInPence($priceInPence)
    {
        $this->_priceInPence = $priceInPence;
    }

    public function getPriceInPence()
    {
        return $this->_priceInPence;
    }

    public function setShortDescription($shortDescription)
    {
        $this->_shortDescription = $shortDescription;
    }

    public function getShortDescription()
    {
        return $this->_shortDescription;
    }

    public function setStockLevel($stockLevel)
    {
        $this->_stockLevel = $stockLevel;
    }

    public function getStockLevel()
    {
        return $this->_stockLevel;
    }

    protected function _getObjectTypeName()
    {
        return self::OBJECT_TYPE_PRODUCT;
    }

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
    }
}