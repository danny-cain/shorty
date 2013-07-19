<?php

namespace CannyDain\Shorty\ECommerce\Models;

use CannyDain\Shorty\ECommerce\Managers\DiscountManager;
use CannyDain\Shorty\ECommerce\Managers\ShippingManager;
use CannyDain\Shorty\ECommerce\Providers\ProductProvider;

class ShortyBasketModel
{
    protected $_id = 0;

    /**
     * @var ShortyBasketItemModel[]
     */
    protected $_items = array();
    protected $_basketCreated = 0;
    protected $_discountInPence = 0;
    protected $_shippingInPence = 0;
    protected $_discountCode = '';

    public function setDiscountCode($discountCode)
    {
        $this->_discountCode = $discountCode;
    }

    public function getDiscountCode()
    {
        return $this->_discountCode;
    }

    public function getDiscountInPence()
    {
        return $this->_discountInPence;
    }

    public function getShippingInPence()
    {
        return $this->_shippingInPence;
    }

    public function __construct()
    {
        $this->_basketCreated = time();
    }

    public function setBasketCreated($basketCreated)
    {
        $this->_basketCreated = $basketCreated;
    }

    public function getBasketCreated()
    {
        return $this->_basketCreated;
    }

    public function addItem(ShortyBasketItemModel $item)
    {
        $this->_items[] = $item;
    }

    public function findItemByGUID($guid)
    {
        foreach ($this->_items as $item)
        {
            if ($item->getProductGUID() == $guid)
                return $item;
        }

        return null;
    }

    public function setId($basketID)
    {
        $this->_id = $basketID;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setItems($items)
    {
        $this->_items = $items;
    }

    public function getItems()
    {
        return $this->_items;
    }

    public function updateBasket(ProductProvider $provider, ShippingManager $shipping, DiscountManager $discount)
    {
        $deletions = array();

        foreach ($this->_items as $index => $basketItem)
        {
            $product = $provider->getProductByGUID($basketItem->getProductGUID());

            if ($product == null || $product->getStockLevel() == 0)
            {
                $deletions[] = $index;
                continue;
            }
            $this->_updateItem($basketItem, $product);
        }

        foreach ($deletions as $index)
            unset($this->_items[$index]);

        $this->_items = array_values($this->_items);

        $this->_discountInPence = $discount->getTotalDiscountInPenceForBasket($this);
        $this->_shippingInPence = $shipping->getTotalShippingInPenceForBasket($this);
    }

    protected function _updateItem(ShortyBasketItemModel $item, ShortyProductModel $product)
    {
        if ($product->getStockLevel() != ShortyProductModel::STOCK_LEVEL_INFINITE && $item->getQty() > $product->getStockLevel())
            $item->setQty($product->getStockLevel());

        $item->setName($product->getName());
        $item->setPriceInPencePerUnit($product->getPriceInPencePerUnit());
        $item->setSummary($product->getSummary());
        $item->setWeightPerUnit($product->getWeightPerUnit());
        $item->setTaxRate($product->getTaxRate());
    }
}