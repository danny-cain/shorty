<?php

namespace CannyDain\ShortyModules\Tasks;

use CannyDain\Shorty\Modules\Base\ShortyModule;
use CannyDain\Shorty\Modules\Models\ModuleInfoModel;
use CannyDain\ShortyModules\Tasks\DataLayer\TasksDataLayer;

class TasksModule extends ShortyModule
{
    const TASKS_MODULE_NAME = __CLASS__;

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
        return new ModuleInfoModel('Tasks Module', 'Danny Cain', '0.1');
    }

    /**
     * @return TasksDataLayer
     */
    public function getDatasource()
    {
        static $datalayer = null;

        if ($datalayer == null)
        {
            $datalayer = new TasksDataLayer();
            $this->_dependencies->applyDependencies($datalayer);
        }

        return $datalayer;
    }
}