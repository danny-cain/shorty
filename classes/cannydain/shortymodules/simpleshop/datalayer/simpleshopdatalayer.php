<?php

namespace CannyDain\ShortyModules\SimpleShop\DataLayer;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\ShortyModules\SimpleShop\Models\Product;

class SimpleShopDatalayer extends ShortyDatasource
{
    public function createProduct()
    {
        $model = new Product();
        $this->_dependencies->applyDependencies($model);

        return $model;
    }

    public function saveProduct(Product $product)
    {
        $this->_datamapper->saveObject($product);
    }

    /**
     * @param $id
     * @return Product
     */
    public function loadProduct($id)
    {
        return $this->_datamapper->loadObject(Product::OBJECT_TYPE_PRODUCT, array('id' => $id));
    }

    /**
     * @return Product[]
     */
    public function getAllProducts()
    {
        return $this->_datamapper->getAllObjects(Product::OBJECT_TYPE_PRODUCT);
    }

    public function registerObjects()
    {
        $file = dirname(__FILE__).'/datadictionary.json';
        $reader = new JSONFileDefinitionBuilder();
        $reader->readFile($file, $this->_datamapper);
    }
}