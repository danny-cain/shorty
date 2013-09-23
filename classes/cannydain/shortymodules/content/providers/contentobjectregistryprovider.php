<?php

namespace CannyDain\ShortyModules\Content\Providers;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Lib\GUIDS\Models\ObjectInfoModel;
use CannyDain\Lib\GUIDS\ObjectRegistryProvider;
use CannyDain\Shorty\Consumers\GUIDConsumer;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\Content\ContentModule;
use CannyDain\ShortyModules\Content\Datasource\ContentDatasource;
use CannyDain\ShortyModules\Content\Models\ContentPage;
use CannyDain\ShortyModules\SimpleShop\DataLayer\SimpleShopDatalayer;
use CannyDain\ShortyModules\SimpleShop\Models\Product;
use CannyDain\ShortyModules\SimpleShop\SimpleShopModule;
use CannyDain\ShortyModules\Tasks\Models\TaskModel;
use CannyDain\ShortyModules\Todo\Datasource\TodoDatasource;
use CannyDain\ShortyModules\Todo\Models\TodoEntry;
use CannyDain\ShortyModules\Todo\TodoModule;
use CannyDain\ShortyModules\Users\Models\User;

class ContentObjectRegistryProvider implements ObjectRegistryProvider, ModuleConsumer, GUIDConsumer
{
    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @var ContentDatasource
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
        // todo content search
    }

    /**
     * @param $guid
     * @return string
     */
    public function getNameOfObject($guid)
    {
        // todo content lookup
    }

    /**
     * @return array
     */
    public function getKnownTypes()
    {
        return array
        (
            ContentPage::TYPE_NAME_CONTENT_PAGE
        );
    }

    public function consumeGUIDManager(GUIDManagerInterface $guidManager)
    {
        $this->_guids = $guidManager;
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        /**
         * @var ContentModule $module
         */
        $module = $manager->getModuleByClassname(ContentModule::CONTENT_MODULE_CLASS);
        if ($module == null || !($module instanceof ContentModule))
            throw new \Exception("Unable to locate content module");

        $this->_datasource = $module->getDatasource();
    }
}