<?php

namespace CannyDain\ShortyCoreModules\Payment_Invoice\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\ShortyCoreModules\Payment_Invoice\Models\InvoiceItemModel;
use CannyDain\ShortyCoreModules\Payment_Invoice\Models\InvoiceModel;
use CannyDain\ShortyCoreModules\Payment_Invoice\Views\InvoiceView;
use CannyDain\ShortyCoreModules\Payment_Invoice\Views\ListInvoicesView;
use CannyDain\ShortyCoreModules\payment_invoice\DataAccess\payment_invoiceDataAccess;

class InvoiceAdminController implements ControllerInterface, DependencyConsumer, RouterConsumer, RequestConsumer
{
    const CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var RouterInterface
     */
    protected $_router;

    public function Index()
    {
        return $this->ListInvoices();
    }

    public function ListInvoices($page = 1)
    {
        $view = $this->_view_ListInvoices($this->datasource()->getInvoicesOrderedByDate($page), $page);

        return $view;
    }

    public function ViewInvoice($id)
    {
        $invoice = $this->datasource()->getInvoiceByID($id);
        $items = $this->datasource()->getInvoiceItemsbyInvoice($id);

        $view = $this->_view_ViewInvoice($invoice, $items);
        if ($this->_request->isPost())
        {
            $view->updateFromRequest($this->_request);
            $this->datasource()->saveInvoice($view->getInvoice());
            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'ViewInvoice', array($id))));
        }

        return $view;
    }

    public function PrintInvoice($id)
    {
        $invoice = $this->datasource()->getInvoiceByID($id);
        $items = $this->datasource()->getInvoiceItemsbyInvoice($id);

        $view = $this->_view_ViewInvoice($invoice, $items);
        $view->setIsPrintView(true);

        return $view;
    }

    /**
     * @param InvoiceModel $invoice
     * @param InvoiceItemModel[] $items
     * @return \CannyDain\ShortyCoreModules\Payment_Invoice\Views\InvoiceView
     */
    protected function _view_ViewInvoice(InvoiceModel $invoice, $items = array())
    {
        $view = new InvoiceView();
        $this->_dependencies->applyDependencies($view);

        $view->setInvoice($invoice);
        $view->setItems($items);
        $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'ViewInvoice', array($invoice->getId()))));

        return $view;
    }

    protected function _view_ListInvoices($invoices, $pageNumber = 1)
    {
        $view = new ListInvoicesView();

        $this->_dependencies->applyDependencies($view);

        $view->setInvoices($invoices);
        $view->setPageNumber($pageNumber);
        $view->setPaginationURLTemplate($this->_router->getURI(new Route(__CLASS__, 'ListInvoices', array('#page#'))));
        $view->setViewLinkTemplate($this->_router->getURI(new Route(__CLASS__, 'ViewInvoice', array('#id#'))));
        $view->setPrintLinkTemplate($this->_router->getURI(new Route(__CLASS__, 'PrintInvoice', array('#id#'))));
        $view->setNumberOfPages(ceil($this->datasource()->countInvoices() / 25));

        return $view;
    }

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return true;
    }

    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new payment_invoiceDataAccess();
            $this->_dependencies->applyDependencies($datasource);
            $datasource->registerObjects();
        }

        return $datasource;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }
}