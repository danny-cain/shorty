<?php

namespace CannyDain\ShortyModules\AddressManagement;

use CannyDain\Shorty\Modules\Base\ShortyModule;
use CannyDain\Shorty\Modules\Models\ModuleInfoModel;
use CannyDain\ShortyModules\AddressManagement\DataLayer\AddressDataSource;

class AddressManagementModule extends ShortyModule
{
    const MODULE_NAME = __CLASS__;
    const CONTROLLER_NAMESPACE = '\\CannyDain\\ShortyModules\\AddressManagement\\Controllers';

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
        return new ModuleInfoModel('Address Module', 'Danny Cain', '0.1');
    }

    /**
     * @return AddressDataSource
     */
    public function getDatasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new AddressDataSource();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }


}