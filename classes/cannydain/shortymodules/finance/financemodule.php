<?php

namespace CannyDain\ShortyModules\Finance;

use CannyDain\ShortyModules\Finance\DataLayer\FinanceDataLayer;
use CannyDain\Shorty\Modules\Base\ShortyModule;
use CannyDain\Shorty\Modules\Models\ModuleInfoModel;

class FinanceModule extends ShortyModule
{
    const FINANCE_MODULE_NAME = __CLASS__;

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
        return new ModuleInfoModel('Finance', 'Danny Cain', '0.1');
    }

    /**
     * @return FinanceDataLayer
     */
    public function getDatasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new FinanceDataLayer();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }
}