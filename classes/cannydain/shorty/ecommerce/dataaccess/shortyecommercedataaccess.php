<?php

namespace CannyDain\Shorty\ECommerce\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\Shorty\ECommerce\Models\ShortyBasketItemModel;
use CannyDain\Shorty\ECommerce\Models\ShortyBasketModel;

class ShortyECommerceDataAccess implements DataMapperConsumer
{
    const OBJECT_BASKET = '\\CannyDain\\Shorty\\ECommerce\\Models\\ShortyBasketModel';
    const OBJECT_BASKET_ITEM = '\\CannyDain\\Shorty\\ECommerce\\Models\\ShortyBasketItemModel';

    /**
     * @var DataMapper
     */
    protected $_datamapper;

    /**
     * @param $id
     * @return ShortyBasketModel
     */
    public function getBasketByID($id)
    {
        /**
         * @var ShortyBasketModel $basket
         */
        $basket = $this->_datamapper->loadObject(self::OBJECT_BASKET, array('id' => $id));
        $basket->setItems($this->getItemsByBasket($basket->getId()));

        return $basket;
    }

    public function saveBasket(ShortyBasketModel $basket)
    {
        $this->_datamapper->saveObject($basket);
    }

    public function saveItem(ShortyBasketItemModel $item)
    {
        $this->_datamapper->saveObject($item);
    }

    public function deleteBasket($id)
    {
        $this->deleteItemsByBasket($id);
        $this->_datamapper->deleteObject(self::OBJECT_BASKET, array('id' => $id));
    }

    public function deleteItemsByBasket($id)
    {
        foreach ($this->getItemsByBasket($id) as $item)
            $this->_datamapper->deleteObject(self::OBJECT_BASKET_ITEM, array('id' => $item->getId()));
    }

    /**
     * @param $basketID
     * @return ShortyBasketItemModel[]
     */
    public function getItemsByBasket($basketID)
    {
        return $this->_datamapper->getAllObjectsViaEqualityFilter(self::OBJECT_BASKET_ITEM, array
        (
            'basket' => $basketID
        ));
    }

    public function registerObjects()
    {
        $file = dirname(dirname(__FILE__)).'/datadictionary/ecommerce.json';
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }
}