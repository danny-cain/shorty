<?php

namespace CannyDain\ShortyModules\Todo;

use CannyDain\Shorty\Modules\Base\ShortyModule;
use CannyDain\Shorty\Modules\Models\ModuleInfoModel;
use CannyDain\ShortyModules\Todo\Datasource\TodoDatasource;

class TodoModule extends ShortyModule
{
    const TODO_MODULE_CLASS = __CLASS__;
    const CONTROLLER_NAMESPACE = '\\CannyDain\\ShortyModules\\Todo\\Controllers';

    /**
     * @return TodoDatasource
     */
    public function getDatasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new TodoDatasource();
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
        return new ModuleInfoModel('Todo Module', 'Danny Cain', '0.1');
    }
}