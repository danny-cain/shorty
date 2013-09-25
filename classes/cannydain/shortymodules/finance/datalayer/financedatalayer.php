<?php

namespace CannyDain\ShortyModules\Finance\DataLayer;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\ObjectPermissions\ObjectPermissionsManagerInterface;
use CannyDain\Shorty\Consumers\ObjectPermissionsConsumer;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\Consumers\UserConsumer;
use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\Shorty\Helpers\UserHelper;
use CannyDain\ShortyModules\Finance\Models\Account;
use CannyDain\ShortyModules\Finance\Models\Transaction;

class FinanceDataLayer extends ShortyDatasource implements UserConsumer, SessionConsumer, ObjectPermissionsConsumer
{
    /**
     * @var SessionHelper
     */
    protected $_session;

    /**
     * @var UserHelper
     */
    protected $_users;

    /**
     * @var ObjectPermissionsManagerInterface
     */
    protected $_permissions;

    /**
     * @param $id
     * @return Account
     */
    public function loadAccount($id)
    {
        return $this->_datamapper->loadObject(Account::OBJECT_TYPE_ACCOUNT, array('id' => $id));
    }

    /**
     * @param $accountID
     * @param $date
     * @return Transaction
     */
    public function getMostRecentBalanceAtDate($accountID, $date)
    {
        return array_shift($this->_datamapper->getObjectsWithCustomClauses(Transaction::OBJECT_TYPE_TRANSACTION, array
        (
            'type = :type',
            'source = :account',
            'destination = :account',
            'transactionDate < :date',
        ), array
        (
            'type' => Transaction::TRANS_TYPE_BALANCE,
            'account' => $accountID,
            'date' => date('Y-m-d H:i:s', $date)
        ), 'transactionDate DESC', 0, 1));
    }

    /**
     * @param $accountID
     * @return int
     */
    public function balanceAccount($accountID)
    {
        $lastBalance = $this->loadLastBalanceForAccount($accountID);
        $transactions = $this->loadTransactionsForAccountSinceLastBalance($accountID);

        if ($lastBalance == null)
            $runningTotal = 0;
        else
            $runningTotal = $lastBalance->getAmount();

        foreach ($transactions as $trans)
        {
            if ($trans->getSourceAccount() == $accountID)
                $runningTotal -= $trans->getAmount();
            else
                $runningTotal += $trans->getAmount();
        }

        $trans = $this->newTransactionModel();
        $trans->setTransactionDateTime(time());
        $trans->setType(Transaction::TRANS_TYPE_BALANCE);
        $trans->setSourceAccount($accountID);
        $trans->setDestinationAccount($accountID);
        $trans->setAmount($runningTotal);
        $trans->setDescription('Balance');
        $trans->save();

        return $runningTotal;
    }

    /**
     * @param $ownerID
     * @return Account[]
     */
    public function getAccountsByOwner($ownerID = null)
    {
        if ($ownerID == null)
            $ownerID = $this->_session->getUserID();

        return $this->_datamapper->getObjectsWithCustomClauses(Account::OBJECT_TYPE_ACCOUNT, array
        (
            'owner = :owner'
        ), array
        (
            'owner' => $ownerID
        ));
    }

    public function newAccountModel()
    {
        $model = new Account();
        $this->_dependencies->applyDependencies($model);

        $model->setOwner($this->_session->getUserID());

        return $model;
    }

    public function newTransactionModel()
    {
        $model = new Transaction();
        $this->_dependencies->applyDependencies($model);

        return $model;
    }

    /**
     * @param $id
     * @return Transaction
     */
    public function loadTransaction($id)
    {
        return $this->_datamapper->loadObject(Transaction::OBJECT_TYPE_TRANSACTION, array('id' => $id));
    }

    public function getBalanceAtDate($accountID, $date)
    {
        $lastBalanceTrans = $this->getMostRecentBalanceAtDate($accountID, $date);
        if ($lastBalanceTrans == null)
        {
            $balance = 0;
            $startDate = 0;
        }
        else
        {
            $balance = $lastBalanceTrans->getAmount();
            $startDate = $lastBalanceTrans->getTransactionDateTime() + 1;
        }

        foreach ($this->loadTransactionsForAccountBetween($accountID, $startDate, $date) as $trans)
        {
            if ($trans->getSourceAccount() == $accountID)
            {
                $balance -= $trans->getAmount();
            }

            if ($trans->getDestinationAccount() == $accountID)
            {
                $balance += $trans->getAmount();
            }
        }

        return $balance;
    }

    /**
     * @param $account
     * @param $startDate
     * @param $endDate
     * @return Transaction[]
     */
    public function loadTransactionsForAccountBetween($account, $startDate, $endDate)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(Transaction::OBJECT_TYPE_TRANSACTION, array
        (
            'transactionDate >= :start',
            'transactionDate <= :end',
            '(destination = :account or source = :account)',
        ), array
        (
            'start' => date('Y-m-d H:i:s', $startDate),
            'end' => date('Y-m-d H:i:s', $endDate),
            'account' => $account,
        ), 'transactionDate ASC');
    }

    /**
     * @param $account
     * @param $startDate
     * @return Transaction[]
     */
    public function loadTransactionsForAccountSince($account, $startDate)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(Transaction::OBJECT_TYPE_TRANSACTION, array
        (
            'transactionDate >= :date',
            '(destination = :account or source = :account)',
        ), array
        (
            'date' => date('Y-m-d H:i:s', $startDate),
            'account' => $account,
        ), 'transactionDate ASC');
    }

    /**
     * @param $account
     * @return Transaction
     */
    public function loadLastBalanceForAccount($account)
    {
        return array_shift($this->_datamapper->getObjectsWithCustomClauses(Transaction::OBJECT_TYPE_TRANSACTION, array
        (
            'type = :type',
            'source = :account',
            'destination = :account'
        ), array
        (
            'type' => Transaction::TRANS_TYPE_BALANCE,
            'account' => $account
        ), 'transactionDate DESC', 0, 1));
    }

    /**
     * @param $account
     * @return Transaction[]
     */
    public function loadTransactionsForAccountSinceLastBalance($account)
    {
        $balance = $this->loadLastBalanceForAccount($account);
        if ($balance == null)
            $date = 0;
        else
            $date = $balance->getTransactionDateTime();

        return $this->_datamapper->getObjectsWithCustomClauses(Transaction::OBJECT_TYPE_TRANSACTION, array
        (
            'transactionDate > :balanceDate',
            '(destination = :account or source = :account)',
            'type = :type'
        ), array
        (
            'balanceDate' => date('Y-m-d H:i:s', $date),
            'account' => $account,
            'type' => Transaction::TRANS_TYPE_TRANSACTION
        ), 'transactionDate ASC');
    }

    /**
     * @return Account[]
     */
    public function getAllAccounts()
    {
        return $this->_datamapper->getAllObjects(Account::OBJECT_TYPE_ACCOUNT);
    }

    public function canAccessTransaction($transactionID, $userID = null)
    {
        return true;
    }

    public function canAccessAccount($accountID, $userID = null)
    {
        return true;
    }

    public function registerObjects()
    {
        $file = dirname(__FILE__).'/datadictionary.json';
        $reader = new JSONFileDefinitionBuilder();
        $reader->readFile($file, $this->_datamapper);
    }

    public function consumeObjectPermissionsManager(ObjectPermissionsManagerInterface $manager)
    {
        $this->_permissions = $manager;
    }

    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }

    public function consumerUserHelper(UserHelper $helper)
    {
        $this->_users = $helper;
    }
}