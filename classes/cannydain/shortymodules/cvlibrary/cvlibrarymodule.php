<?php

namespace CannyDain\ShortyModules\CVLibrary;

use CannyDain\Shorty\Modules\Base\ShortyModule;
use CannyDain\Shorty\Modules\Models\ModuleInfoModel;
use CannyDain\ShortyModules\CVLibrary\Datasource\CVLibraryDatasource;

class CVLibraryModule extends ShortyModule
{
    const MODULE_NAME = __CLASS__;
    const CONTROLLER_NAMESPACE = '\\CannyDain\\ShortyModules\\CVLibrary\\Controllers';

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
        return new ModuleInfoModel('CVLibrary', 'Danny Cain', '0.1');
    }

    /**
     * @return CVLibraryDatasource
     */
    public function getDatasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new CVLibraryDatasource();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }
}