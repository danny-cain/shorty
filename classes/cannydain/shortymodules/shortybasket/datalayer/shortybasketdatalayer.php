<?php

namespace CannyDain\ShortyModules\ShortyBasket\DataLayer;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\ShortyModules\ShortyBasket\Models\BasketItemModel;
use CannyDain\ShortyModules\ShortyBasket\Models\BasketModel;

class ShortyBasketDatalayer extends ShortyDatasource
{
    /**
     * @param $basketID
     * @return BasketItemModel[]
     */
    public function getBasketItems($basketID)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(BasketItemModel::BASKET_ITEM_OBJECT_TYPE, array
        (
            'basket = :basket'
        ), array
        (
            'basket' => $basketID
        ));
    }

    public function createBasketItem()
    {
        $basketItem = new BasketItemModel();
        $this->_dependencies->applyDependencies($basketItem);

        return $basketItem;
    }

    public function createBasket()
    {
        $basket = new BasketModel();
        $this->_dependencies->applyDependencies($basket);

        return $basket;
    }

    public function registerObjects()
    {
        $file = dirname(__FILE__).'/datadictionary.json';
        $reader = new JSONFileDefinitionBuilder();
        $reader->readFile($file, $this->_datamapper);
    }
}