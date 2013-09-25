<?php

namespace CannyDain\ShortyModules\Finance\Providers;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Lib\GUIDS\Models\ObjectInfoModel;
use CannyDain\Lib\GUIDS\ObjectRegistryProvider;
use CannyDain\Shorty\Consumers\GUIDConsumer;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\ShortyModules\Finance\DataLayer\FinanceDataLayer;
use CannyDain\ShortyModules\Finance\Models\Account;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\Finance\FinanceModule;
use CannyDain\ShortyModules\Finance\Models\Transaction;

class FinanceObjectProvider implements ObjectRegistryProvider, ModuleConsumer, GUIDConsumer
{
    /**
     * @var FinanceDataLayer
     */
    protected $_datasource;

    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @param string $searchTerm
     * @param string $typeLimit
     * @param int $limit
     * @return ObjectInfoModel[]
     */
    public function searchObjects($searchTerm, $typeLimit = null, $limit = 0)
    {
        // todo - search accounts (and maybe transaction descriptions)
        return array();
    }

    /**
     * @param $guid
     * @return string
     */
    public function getNameOfObject($guid)
    {
        $type = $this->_guids->getType($guid);
        $id = $this->_guids->getID($guid);

        switch($type)
        {
            case Account::OBJECT_TYPE_ACCOUNT:
                $account = $this->_datasource->loadAccount($id);
                return $account->getName();
                break;
            case Transaction::OBJECT_TYPE_TRANSACTION:
                $trans = $this->_datasource->loadTransaction($id);
                return $trans->getDescription();
                break;
        }
        return '';
    }

    /**
     * @return array
     */
    public function getKnownTypes()
    {
        return array
        (
            Account::OBJECT_TYPE_ACCOUNT,
            Transaction::OBJECT_TYPE_TRANSACTION
        );
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        /**
         * @var FinanceModule $module
         */
        $module = $manager->getModuleByClassname(FinanceModule::FINANCE_MODULE_NAME);
        if ($module == null || !($module instanceof FinanceModule))
            throw new \Exception("Unable to locate finance module");

        $this->_datasource = $module->getDatasource();
    }

    public function consumeGUIDManager(GUIDManagerInterface $guidManager)
    {
        $this->_guids = $guidManager;
    }
}