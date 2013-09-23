<?php

namespace CannyDain\ShortyModules\SimpleShop\Providers;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Lib\GUIDS\Models\ObjectInfoModel;
use CannyDain\Lib\GUIDS\ObjectRegistryProvider;
use CannyDain\Shorty\Consumers\GUIDConsumer;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\SimpleShop\DataLayer\SimpleShopDatalayer;
use CannyDain\ShortyModules\SimpleShop\Models\Product;
use CannyDain\ShortyModules\SimpleShop\SimpleShopModule;
use CannyDain\ShortyModules\Tasks\Models\TaskModel;
use CannyDain\ShortyModules\Todo\Datasource\TodoDatasource;
use CannyDain\ShortyModules\Todo\Models\TodoEntry;
use CannyDain\ShortyModules\Todo\TodoModule;
use CannyDain\ShortyModules\Users\Models\User;

class SimpleShopObjectRegistryProvider implements ObjectRegistryProvider, ModuleConsumer, GUIDConsumer
{
    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @var SimpleShopDatalayer
     */
    protected $_datasource;

    /**
     * @param string $searchTerm
     * @param string $typeLimit
     * @param int $limit
     * @return ObjectInfoModel[]
     */
    public function searchObjects($searchTerm, $typeLimit = null, $limit = 0)
    {
        // todo product search
    }

    /**
     * @param $guid
     * @return string
     */
    public function getNameOfObject($guid)
    {
        // todo product lookup
    }

    /**
     * @return array
     */
    public function getKnownTypes()
    {
        return array
        (
            Product::OBJECT_TYPE_PRODUCT
        );
    }

    public function consumeGUIDManager(GUIDManagerInterface $guidManager)
    {
        $this->_guids = $guidManager;
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        /**
         * @var SimpleShopModule $module
         */
        $module = $manager->getModuleByClassname(SimpleShopModule::SIMPLE_SHOP_MODULE_NAME);
        if ($module == null || !($module instanceof SimpleShopModule))
            throw new \Exception("Unable to locate simple shop module");

        $this->_datasource = $module->getDatasource();
    }
}