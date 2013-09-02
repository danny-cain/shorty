<?php

namespace CannyDain\ShortyModules\SimpleShop;

use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\Shorty\Modules\Base\ShortyModule;
use CannyDain\Shorty\Modules\Models\ModuleInfoModel;
use CannyDain\ShortyModules\SimpleShop\DataLayer\SimpleShopDatalayer;

class SimpleShopModule extends ShortyModule
{
    const SIMPLE_SHOP_MODULE_NAME = __CLASS__;

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
        return new ModuleInfoModel('Simple Shop', 'Danny Cain', '0.1');
    }

    /**
     * @return SimpleShopDatalayer
     */
    public function getDatasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new SimpleShopDatalayer();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }
}