<?php

namespace CannyDain\ShortyModules\Content;

use CannyDain\Shorty\Modules\Base\ShortyModule;
use CannyDain\Shorty\Modules\Models\ModuleInfoModel;
use CannyDain\ShortyModules\Content\Datasource\ContentDatasource;

class ContentModule extends ShortyModule
{
    const CONTENT_MODULE_CLASS = __CLASS__;
    const CONTROLLER_NAMESPACE = '\\CannyDain\\ShortyModules\\Content\\Controllers';

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

    }

    /**
     * @return ModuleInfoModel
     */
    public function getInfo()
    {
        return new ModuleInfoModel('Content Module', 'Danny Cain', '0.1');
    }
}