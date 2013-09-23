<?php

namespace CannyDain\ShortyModules\Invoice\Providers;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Lib\GUIDS\Models\ObjectInfoModel;
use CannyDain\Lib\GUIDS\ObjectRegistryProvider;
use CannyDain\Shorty\Consumers\GUIDConsumer;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\Invoice\DataLayer\InvoiceDatasource;
use CannyDain\ShortyModules\Invoice\InvoiceModule;
use CannyDain\ShortyModules\Invoice\Models\Invoice;
use CannyDain\ShortyModules\SimpleShop\DataLayer\SimpleShopDatalayer;
use CannyDain\ShortyModules\SimpleShop\Models\Product;
use CannyDain\ShortyModules\SimpleShop\SimpleShopModule;
use CannyDain\ShortyModules\Tasks\Models\TaskModel;
use CannyDain\ShortyModules\Todo\Datasource\TodoDatasource;
use CannyDain\ShortyModules\Todo\Models\TodoEntry;
use CannyDain\ShortyModules\Todo\TodoModule;
use CannyDain\ShortyModules\Users\Models\User;

class InvoiceObjectRegistryProvider implements ObjectRegistryProvider, ModuleConsumer, GUIDConsumer
{
    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @var InvoiceDatasource
     */
    protected $_datasource;

    /**
     * @param string $searchTerm
     * @param string $typeLimit
     * @param int $limit
     * @return ObjectInfoModel[]
     */
    public function searchObjects($searchTerm, $typeLimit = null, $limit = 0)
    {
        // todo invoice search
    }

    /**
     * @param $guid
     * @return string
     */
    public function getNameOfObject($guid)
    {
        // todo invoice lookup
    }

    /**
     * @return array
     */
    public function getKnownTypes()
    {
        return array
        (
            Invoice::OBJECT_TYPE_INVOICE
        );
    }

    public function consumeGUIDManager(GUIDManagerInterface $guidManager)
    {
        $this->_guids = $guidManager;
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        /**
         * @var InvoiceModule $module
         */
        $module = $manager->getModuleByClassname(InvoiceModule::INVOICE_MODULE_NAME);
        if ($module == null || !($module instanceof InvoiceModule))
            throw new \Exception("Unable to locate invoice module");

        $this->_datasource = $module->getDatasource();
    }
}