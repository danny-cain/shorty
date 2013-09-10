<?php

namespace CannyDain\ShortyModules\ShortyBasket\Models;

use CannyDain\Shorty\Models\ShortyModel;

class BasketModel extends ShortyModel
{
    const BASKET_OBJECT_TYPE = __CLASS__;

    protected $_id = 0;

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
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