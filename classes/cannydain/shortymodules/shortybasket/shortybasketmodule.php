<?php

namespace CannyDain\ShortyModules\ShortyBasket;

use CannyDain\Shorty\Modules\Base\ShortyModule;
use CannyDain\Shorty\Modules\Models\ModuleInfoModel;
use CannyDain\ShortyModules\ShortyBasket\DataLayer\ShortyBasketDatalayer;

class ShortyBasketModule extends ShortyModule
{
    const SHORTY_BASKET_MODULE_NAME = __CLASS__;
    const CONTROLLER_NAMESPACE = '\\CannyDain\\ShortyModules\\ShortyBasket\\Controllers';

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
        return new ModuleInfoModel('Shorty Basket', 'Danny Cain', '0.1');
    }

    /**
     * @return ShortyBasketDatalayer
     */
    public function getDatasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new ShortyBasketDatalayer();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }
}