<?php

namespace CannyDain\ShortyCoreModules\SimpleShop\Providers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\ECommerce\Models\ShortyProductModel;
use CannyDain\Shorty\ECommerce\Providers\ProductProvider;
use CannyDain\ShortyCoreModules\SimpleShop\DataAccess\SimpleShopDataAccess;

class SimpleShopProductProvider implements ProductProvider, DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @param $guid
     * @return ShortyProductModel
     */
    public function getProductByGUID($guid)
    {
        $ssProduct = $this->datasource()->getProductByGUID($guid);

        if ($ssProduct == null)
            return null;

        $ret = new ShortyProductModel();

        $ret->setGuid($guid);
        $ret->setName($ssProduct->getName());
        $ret->setPriceInPencePerUnit($ssProduct->getPrice());
        $ret->setStockLevel($ssProduct->getStockLevel());
        $ret->setSummary($ssProduct->getSummary());
        $ret->setWeightPerUnit($ssProduct->getWeight());
        $ret->setTaxRate($ssProduct->getTaxRate());

        return $ret;
    }

    public function reduceStock($productGUID, $amount, $reason)
    {
        $product = $this->datasource()->getProductByGUID($productGUID);
        if ($product == null)
            return;

        if ($product->getStockLevel() == -1)
            return;

        $product->setStockLevel($product->getStockLevel() - $amount);
        if ($product->getStockLevel() < 0)
            $product->setStockLevel(0);

        $this->datasource()->saveProduct($product);
    }

    public function increaseStock($productGUID, $amount, $reason)
    {
        $product = $this->datasource()->getProductByGUID($productGUID);
        if ($product == null)
            return;

        if ($product->getStockLevel() == -1)
            return;

        $product->setStockLevel($product->getStockLevel() + $amount);
        $this->datasource()->saveProduct($product);
    }

    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new SimpleShopDataAccess();
            $this->_dependencies->applyDependencies($datasource);
            $this->datasource()->registerObjects();
        }

        return $datasource;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }
}