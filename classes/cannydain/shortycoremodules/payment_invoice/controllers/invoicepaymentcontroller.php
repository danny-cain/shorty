<?php

namespace CannyDain\ShortyCoreModules\Payment_Invoice\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\ECommerceConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\ECommerce\ECommerceManager;
use CannyDain\ShortyCoreModules\Payment_Invoice\Models\InvoiceItemModel;
use CannyDain\ShortyCoreModules\Payment_Invoice\Models\InvoiceModel;
use CannyDain\ShortyCoreModules\Payment_Invoice\Views\InvoiceDetailsView;
use CannyDain\ShortyCoreModules\payment_invoice\DataAccess\payment_invoiceDataAccess;

class InvoicePaymentController implements ControllerInterface, DependencyConsumer, RouterConsumer, RequestConsumer, ECommerceConsumer
{
    const CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var ECommerceManager
     */
    protected $_ecommerce;

    public function Pay()
    {
        $this->_ecommerce->updateBasket();

        $view = new InvoiceDetailsView();
        $this->_dependencies->applyDependencies($view);

        $view->setUpdateURI($this->_router->getURI(new Route(__CLASS__, 'Pay')));
        $view->setInvoice(new InvoiceModel());

        if ($this->_request->isPost())
        {
            $view->updateFromRequest($this->_request);
            $this->datasource()->saveInvoice($view->getInvoice());
            $this->datasource()->deleteInvoiceItemsByInvoice($view->getInvoice()->getId());

            foreach ($this->_ecommerce->getCurrentBasketAndCreateIfOneDoesntExist()->getItems() as $item)
            {
                $invoiceItem = new InvoiceItemModel();
                $invoiceItem->setInvoiceID($view->getInvoice()->getId());
                $invoiceItem->setItemName($item->getName());
                $invoiceItem->setPricePerUnit($item->getPriceInPencePerUnit());
                $invoiceItem->setQty($item->getQty());
                $invoiceItem->setTaxRate($item->getTaxRate());

                $this->datasource()->saveInvoiceItem($invoiceItem);
            }

            $view->getInvoice()->setDiscountInPence($this->_ecommerce->getCurrentBasket()->getDiscountInPence());
            $view->getInvoice()->setShippingInPence($this->_ecommerce->getCurrentBasket()->getShippingInPence());
            $view->getInvoice()->setStatus(InvoiceModel::STATUS_TO_BE_SENT);
            $this->datasource()->saveInvoice($view->getInvoice());

            $this->_ecommerce->deleteCurrentBasket();

            return new RedirectView('/');
        }

        return $view;
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

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return false;
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

    public function consumeECommerceManager(ECommerceManager $dependency)
    {
        $this->_ecommerce = $dependency;
    }
}