<?php

namespace CannyDain\ShortyModules\MailHistory;

use CannyDain\Shorty\Modules\Base\ShortyModule;
use CannyDain\Shorty\Modules\Models\ModuleInfoModel;
use CannyDain\ShortyModules\MailHistory\Datasource\MailHistoryDatasource;

class MailHistoryModule extends ShortyModule
{
    const MODULE_NAME = __CLASS__;
    const CONTROLLER_PATH = '\\CannyDain\\ShortyModules\\MailHistory\\Controller';

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
        return new ModuleInfoModel('Mail History', 'Danny Cain', '0.1');
    }

    /**
     * @return MailHistoryDatasource
     */
    public function getDatasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new MailHistoryDatasource();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }
}