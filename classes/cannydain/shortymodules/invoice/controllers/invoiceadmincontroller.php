<?php

namespace CannyDain\ShortyModules\Invoice\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Controllers\ShortyModuleController;
use CannyDain\Shorty\Geo\Views\InvoicePDFView;
use CannyDain\Shorty\RouteAccessControl\RouteAccessControlInterface;
use CannyDain\ShortyModules\Invoice\InvoiceModule;
use CannyDain\ShortyModules\Invoice\Views\InvoiceAdminView;
use CannyDain\ShortyModules\Invoice\Views\InvoiceListView;

class InvoiceAdminController extends ShortyModuleController
{
    public function Index()
    {
        return $this->ViewList();
    }

    public function View($id)
    {
        $view = new InvoiceAdminView();
        $invoice = $this->_getModule()->getDatasource()->getInvoiceByID($id);
        $items = $this->_getModule()->getDatasource()->getInvoiceItemsByInvoiceID($id);

        $view->setInvoice($invoice);
        $view->setItems($items);

        return $view;
    }

    public function ViewList()
    {
        $view = new InvoiceListView();
        $this->_dependencies->applyDependencies($view);

        $view->setDatasource($this->_getModule()->getDatasource());
        $view->setViewListRoute(new Route(__CLASS__, 'ViewList'));
        $view->setViewInvoiceRoute(new Route(__CLASS__, 'View', array('#id#')));

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            $invoices = $this->_getModule()->getDatasource()->getFilteredInvoices($view->getStatusFilter(), $view->getStartDateFilter(), $view->getEndDateFilter());
            $view->setInvoices($invoices);
        }

        return $view;
    }

    public function Download($invoiceID)
    {
        $view = new InvoicePDFView();

        $view->setInvoice($this->_getModule()->getDatasource()->getInvoiceByID($invoiceID));
        $view->setItems($this->_getModule()->getDatasource()->getInvoiceItemsByInvoiceID($invoiceID));

        return $view;
    }

    protected function _getModuleClassname()
    {
        return InvoiceModule::INVOICE_MODULE_NAME;
    }

    public function getDefaultMinimumAccessLevel()
    {
        return RouteAccessControlInterface::ACCESS_LEVEL_ADMIN;
    }

    /**
     * @return InvoiceModule
     */
    protected function _getModule()
    {
        return parent::_getModule();
    }


}