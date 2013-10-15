<?php

namespace CannyDain\ShortyModules\Stories;

use CannyDain\Shorty\Modules\Base\ShortyModule;
use CannyDain\Shorty\Modules\Models\ModuleInfoModel;
use CannyDain\ShortyModules\Stories\DataLayer\StoryDatalayer;

class StoriesModule extends ShortyModule
{
    const STORY_MODULE_NAME = __CLASS__;
    const CONTROLLER_NAMESPACE = '\\CannyDain\\ShortyModules\\Stories\\Controllers';

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
        return new ModuleInfoModel("Stories", "Danny Cain", "0.1");
    }

    /**
     * @return StoryDatalayer
     */
    public function getDatasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new StoryDatalayer();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }
}