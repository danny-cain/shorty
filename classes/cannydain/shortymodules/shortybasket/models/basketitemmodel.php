<?php

namespace CannyDain\ShortyModules\ShortyBasket\Models;

use CannyDain\Shorty\Models\ShortyModel;

class BasketItemModel extends ShortyModel
{
    const BASKET_ITEM_OBJECT_TYPE = __CLASS__;

    protected $_id = 0;
    protected $_basketID = 0;
    protected $_productGUID = '';
    protected $_quantity = 0;

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
    }

    public function setBasketID($basketID)
    {
        $this->_basketID = $basketID;
    }

    public function getBasketID()
    {
        return $this->_basketID;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setProductGUID($productGUID)
    {
        $this->_productGUID = $productGUID;
    }

    public function getProductGUID()
    {
        return $this->_productGUID;
    }

    public function setQuantity($qty)
    {
        $this->_quantity = $qty;
    }

    public function getQuantity()
    {
        return $this->_quantity;
    }
}