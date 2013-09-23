<?php

namespace CannyDain\ShortyModules\Comments\Providers;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Lib\GUIDS\Models\ObjectInfoModel;
use CannyDain\Lib\GUIDS\ObjectRegistryProvider;
use CannyDain\Shorty\Consumers\GUIDConsumer;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\Comments\CommentsModule;
use CannyDain\ShortyModules\Comments\Datasource\CommentsDatasource;
use CannyDain\ShortyModules\Comments\Models\Comment;
use CannyDain\ShortyModules\SimpleShop\DataLayer\SimpleShopDatalayer;
use CannyDain\ShortyModules\SimpleShop\Models\Product;
use CannyDain\ShortyModules\SimpleShop\SimpleShopModule;
use CannyDain\ShortyModules\Tasks\Models\TaskModel;
use CannyDain\ShortyModules\Todo\Datasource\TodoDatasource;
use CannyDain\ShortyModules\Todo\Models\TodoEntry;
use CannyDain\ShortyModules\Todo\TodoModule;
use CannyDain\ShortyModules\Users\Models\User;

class CommentsObjectRegistryProvider implements ObjectRegistryProvider, ModuleConsumer, GUIDConsumer
{
    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @var CommentsDatasource
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
        // todo comment search
    }

    /**
     * @param $guid
     * @return string
     */
    public function getNameOfObject($guid)
    {
        // todo comment lookup
    }

    /**
     * @return array
     */
    public function getKnownTypes()
    {
        return array
        (
            Comment::COMMENT_OBJECT_TYPE
        );
    }

    public function consumeGUIDManager(GUIDManagerInterface $guidManager)
    {
        $this->_guids = $guidManager;
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        /**
         * @var CommentsModule $module
         */
        $module = $manager->getModuleByClassname(CommentsModule::COMMENTS_MODULE_CLASS);
        if ($module == null || !($module instanceof CommentsModule))
            throw new \Exception("Unable to locate comments module");

        $this->_datasource = $module->getDatasource();
    }
}