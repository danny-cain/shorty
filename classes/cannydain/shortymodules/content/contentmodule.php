<?php

namespace CannyDain\ShortyModules\Content;

use CannyDain\Shorty\Consumers\RouteManagerConsumer;
use CannyDain\Shorty\Modules\Base\ShortyModule;
use CannyDain\Shorty\Modules\Models\ModuleInfoModel;
use CannyDain\Shorty\Routing\RouteManager;
use CannyDain\ShortyModules\Content\Datasource\ContentDatasource;
use CannyDain\ShortyModules\Content\Providers\ContentRouteProvider;

class ContentModule extends ShortyModule implements RouteManagerConsumer
{
    const CONTENT_MODULE_CLASS = __CLASS__;
    const CONTROLLER_NAMESPACE = '\\CannyDain\\ShortyModules\\Content\\Controllers';

    /**
     * @var RouteManager
     */
    protected $_routeManager;

    /**
     * @return ContentDatasource
     */
    public function getDatasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new ContentDatasource();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    /**
     * Allows the module to perform any initialisation actions (i.e. loading in session etc)
     * @return void
     */
    public function initialise()
    {
        $this->_routeManager->addProvider(new ContentRouteProvider($this->getDatasource()));
    }

    /**
     * @return ModuleInfoModel
     */
    public function getInfo()
    {
        return new ModuleInfoModel('Content Module', 'Danny Cain', '0.1');
    }

    public function consumeRouteManager(RouteManager $manager)
    {
        $this->_routeManager = $manager;
    }
}