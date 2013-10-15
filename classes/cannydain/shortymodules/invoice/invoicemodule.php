<?php

namespace CannyDain\ShortyModules\Invoice;

use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\Shorty\Modules\Base\ShortyModule;
use CannyDain\Shorty\Modules\Models\ModuleInfoModel;
use CannyDain\ShortyModules\Invoice\DataLayer\InvoiceDatasource;
use CannyDain\ShortyModules\SimpleShop\DataLayer\SimpleShopDatalayer;

class InvoiceModule extends ShortyModule
{
    const INVOICE_MODULE_NAME = __CLASS__;
    const CONTROLLER_NAMESPACE = '\\CannyDain\\ShortyModules\\Invoice\\Controllers';

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
        return new ModuleInfoModel('Invoice', 'Danny Cain', '0.1');
    }

    /**
     * @return InvoiceDatasource
     */
    public function getDatasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new InvoiceDatasource();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }
}