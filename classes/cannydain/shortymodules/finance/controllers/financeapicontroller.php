<?php

namespace CannyDain\ShortyModules\Finance\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\JSONView;
use CannyDain\Lib\UI\Views\PlainTextView;
use CannyDain\Shorty\Controllers\ShortyModuleController;
use CannyDain\Shorty\RouteAccessControl\RouteAccessControlInterface;
use CannyDain\ShortyModules\Finance\FinanceModule;
use CannyDain\ShortyModules\Finance\Models\Account;
use CannyDain\ShortyModules\Finance\Models\Transaction;

class FinanceAPIController extends ShortyModuleController
{
    const CONTROLLER_NAME = __CLASS__;

    public function getDefaultMinimumAccessLevel()
    {
        return RouteAccessControlInterface::ACCESS_LEVEL_MEMBER;
    }

    protected function _convertAccountToAssociativeArray(Account $account)
    {
        return array
        (
            'id' => $account->getId(),
            'name' => $account->getName(),
            'owner' => $account->getOwner(),
            'guid' => $account->getGUID(),
        );
    }

    protected function _convertTransactionToAssociativeArray(Transaction $trans)
    {
        return array
        (
            'id' => $trans->getId(),
            'date' => date('Y-m-d H:i:s', $trans->getTransactionDateTime()),
            'amount' => $trans->getAmount(),
            'description' => $trans->getDescription(),
            'destination' => $trans->getDestinationAccount(),
            'source' => $trans->getSourceAccount(),
            'type' => $trans->getType(),
        );
    }

    public function jsClient()
    {
        $file = dirname(dirname(__FILE__)).'/data/client.js';

        $js = file_get_contents($file);
        $js = strtr($js, array
        (
            '#listAccountsURI#' => $this->_router->getURI(new Route(__CLASS__, 'listAccounts')),
            '#getBalanceURI#' => $this->_router->getURI(new Route(__CLASS__, 'getBalanceForDate', array('#account#', '#date#'))),
            '#createTransactionURI#' => $this->_router->getURI(new Route(__CLASS__, 'createTransaction')),
            '#getAccountDetailsForDateURI#' => $this->_router->getURI(new Route(__CLASS__, 'getAccountDetailsForDate', array('#account#', '#start#', '#end#'))),
            '#balanceAccountURI#' => $this->_router->getURI(new Route(__CLASS__, 'balanceAccount', array('#account#'))),
            '#getLastBalanceDate#' => $this->_router->getURI(new Route(__CLASS__, 'getLastBalanceDate', array('#account#'))),
        ));

        return new PlainTextView($js, 'application/javascript');
    }

    public function getLastBalanceDate($accountID)
    {
        if (!$this->_getModule()->getDatasource()->canAccessAccount($accountID))
            return new JSONView(array('status' => 'failed'));

        $balance = $this->_getModule()->getDatasource()->loadLastBalanceForAccount($accountID);
        if ($balance == null)
            $lastBalanceDate = 'not balanced';
        else
            $lastBalanceDate = date('Y-m-d', $balance->getTransactionDateTime());

        return new JSONView(array('status' => 'ok', 'date' => $lastBalanceDate));
    }

    public function getBalanceForDate($accountID, $date = null)
    {
        if (!$this->_getModule()->getDatasource()->canAccessAccount($accountID))
            return new JSONView(array('status' => 'failed'));

        if ($date === null || $date === '')
            $date = time();
        else
            $date = strtotime($date);

        $ret = array
        (
            'status' => 'ok',
            'balance' => $this->_getModule()->getDatasource()->getBalanceAtDate($accountID, $date)
        );

        return new JSONView($ret);
    }

    public function createAccount($name)
    {
        $account = $this->_getModule()->getDatasource()->newAccountModel();
        $account->save();

        return new JSONView(array
        (
            'status' => 'ok',
            'account' => $this->_convertAccountToAssociativeArray($account)
        ));
    }

    public function balanceAccount($id)
    {
        if (!$this->_getModule()->getDatasource()->canAccessAccount($id))
            return new JSONView(array('status' => 'failed'));

        $balance = $this->_getModule()->getDatasource()->balanceAccount($id);
        return new JSONView(array('status' => 'ok', 'balance' => $balance));
    }

    public function balanceMyAccounts()
    {
        $ret = array();
        foreach ($this->_getModule()->getDatasource()->getAccountsByOwner(null) as $account)
        {
            $ret[] = array
            (
                'account' => $this->_convertAccountToAssociativeArray($account),
                'balance' => $this->_getModule()->getDatasource()->balanceAccount($account->getId())
            );
        }

        return new JSONView($ret);
    }

    public function createTransaction()
    {
        if (!$this->_request->isPost())
            return new JSONView(array('status' => 'failed'));

        $sourceAccount = $this->_request->getParameter('source');
        $destAccount = $this->_request->getParameter('dest');
        $description = $this->_request->getParameter('description');
        $amount = $this->_request->getParameter('amount');

        $source = $this->_getModule()->getDatasource()->loadAccount($sourceAccount);
        $dest = $this->_getModule()->getDatasource()->loadAccount($destAccount);

        if (!$this->_getModule()->getDatasource()->canAccessAccount($source))
            return new JSONView(array('status' => 'failed'));

        if (!$this->_getModule()->getDatasource()->canAccessAccount($dest))
            return new JSONView(array('status' => 'failed'));

        $trans = $this->_getModule()->getDatasource()->newTransactionModel();
        $trans->setSourceAccount($sourceAccount);
        $trans->setDestinationAccount($destAccount);
        $trans->setDescription($description);
        $trans->setAmount($amount);
        $trans->setTransactionDateTime(time());
        $trans->setType(Transaction::TRANS_TYPE_TRANSACTION);
        $trans->save();

        return new JSONView(array('status' => 'ok', 'transaction' => $this->_convertTransactionToAssociativeArray($trans)));
    }

    public function getAccountDetailsForDate($accountID, $startDate, $endDate)
    {
        if (!$this->_getModule()->getDatasource()->canAccessAccount($accountID))
            return new JSONView(array('status' => 'failed'));

        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        $account = $this->_getModule()->getDatasource()->loadAccount($accountID);
        $ret = array
        (
            'status' => 'ok',
            'account' => $this->_convertAccountToAssociativeArray($account),
            'transactions' => array(),
            'openingBalance' => $this->_getModule()->getDatasource()->getBalanceAtDate($accountID, $startDate - 1),
            'start' => date('Y-m-d H:i', $startDate),
            'end' => date('Y-m-d H:i', $endDate)
        );

        foreach ($this->_getModule()->getDatasource()->loadTransactionsForAccountBetween($accountID, $startDate, $endDate) as $transaction)
        {
            if (!$this->_getModule()->getDatasource()->canAccessTransaction($transaction->getId()))
                continue;

            $ret['transactions'][] = $this->_convertTransactionToAssociativeArray($transaction);
        }

        return new JSONView($ret);
    }

    public function getTransactionsSince($accountID, $startDate)
    {
        $ret = array();

        foreach ($this->_getModule()->getDatasource()->loadTransactionsForAccountSince($accountID, strtotime($startDate)) as $transaction)
        {
            if (!$this->_getModule()->getDatasource()->canAccessTransaction($transaction->getId()))
                continue;

            $ret[] = $this->_convertTransactionToAssociativeArray($transaction);
        }

        return new JSONView($ret);
    }

    public function listAccounts()
    {
        $ret = array();

        foreach ($this->_getModule()->getDatasource()->getAllAccounts() as $account)
        {
            if (!$this->_getModule()->getDatasource()->canAccessAccount($account->getId()))
                continue;

            $ret[] = $this->_convertAccountToAssociativeArray($account);
        }

        return new JSONView($ret);
    }

    public function listRecentTransactions($accountID)
    {
        $ret = array();

        foreach ($this->_getModule()->getDatasource()->loadTransactionsForAccountSinceLastBalance($accountID) as $trans)
        {
            if (!$this->_getModule()->getDatasource()->canAccessTransaction($trans))
                continue;

            $ret[] = $this->_convertTransactionToAssociativeArray($trans);
        }

        return new JSONView($ret);
    }


    protected function _getModuleClassname()
    {
        return FinanceModule::FINANCE_MODULE_NAME;
    }

    /**
     * @return FinanceModule
     */
    protected function _getModule()
    {
        return parent::_getModule();
    }
}