<?php

namespace CannyDain\ShortyCoreModules\SimpleShop\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\Shorty\Consumers\GUIDManagerConsumer;
use CannyDain\ShortyCoreModules\SimpleShop\Models\SimpleShopProduct;

class SimpleShopDataAccess implements DataMapperConsumer, GUIDManagerConsumer
{
    const OBJECT_PRODUCT = '\\CannyDain\\ShortyCoreModules\\SimpleShop\\Models\\SimpleShopProduct';

    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @var DataMapper
     */
    protected $_datamapper;

    public function saveProduct(SimpleShopProduct $product)
    {
        $this->_datamapper->saveObject($product);
    }

    /**
     * @param $guid
     * @return SimpleShopProduct|null
     */
    public function getProductByGUID($guid)
    {
        $id = $this->_guids->getID($guid);
        $type = $this->_guids->getType($guid);

        if ($type != self::OBJECT_PRODUCT)
            return null;

        return $this->getProduct($id);
    }

    public function getProductGUID($productID)
    {
        return $this->_guids->getGUID(self::OBJECT_PRODUCT, $productID);
    }

    /**
     * @param $id
     * @return SimpleShopProduct
     */
    public function getProduct($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_PRODUCT, array('id' => $id));
    }

    public function registerObjects()
    {
        $file = dirname(dirname(__FILE__)).'/datadictionary/objects.json';
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);
    }

    /**
     * @return SimpleShopProduct
     */
    public function getAllProducts()
    {
        return $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_PRODUCT, array(), array(), 'name ASC');
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }

    public function consumeGUIDManager(GUIDManagerInterface $dependency)
    {
        $this->_guids = $dependency;
    }
}