<?php

namespace CannyDain\ShortyModules\SimpleShop\Providers;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Shorty\Consumers\GUIDConsumer;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\ECommerce\Products\Models\ProductInfoModel;
use CannyDain\Shorty\ECommerce\Products\ProductProviderInterface;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\SimpleShop\Models\Product;
use CannyDain\ShortyModules\SimpleShop\SimpleShopModule;

class SimpleShopProductProvider implements ProductProviderInterface, ModuleConsumer, GUIDConsumer
{
    /**
     * @var SimpleShopModule
     */
    protected $_module;

    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @param $guid
     * @return ProductInfoModel
     */
    public function getProductInfo($guid)
    {
        $type = $this->_guids->getType($guid);
        if ($type != Product::OBJECT_TYPE_PRODUCT)
            return null;
        $id = $this->_guids->getID($guid);

        $datasource = $this->_module->getDatasource();
        $product = $datasource->loadProduct($id);
        if ($product == null || $product->getId() < 1)
            return null;


        $ret = new ProductInfoModel;
        $ret->setName($product->getName());
        $ret->setPricePerUnitInPence($product->getPriceInPence());
        $ret->setDescription($product->getShortDescription());
        $ret->setGuid($guid);
        $ret->setStockLevel($product->getStockLevel());
        $ret->setTaxRate(0.175);

        return $ret;
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        $this->_module = $manager->getModuleByClassname(SimpleShopModule::SIMPLE_SHOP_MODULE_NAME);
    }

    public function consumeGUIDManager(GUIDManagerInterface $guidManager)
    {
        $this->_guids = $guidManager;
    }
}