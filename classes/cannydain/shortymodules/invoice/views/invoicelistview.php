<?php

namespace CannyDain\ShortyModules\Invoice\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\AddressManagerConsumer;
use CannyDain\Shorty\Finance\Interfaces\InvoiceInterface;
use CannyDain\Shorty\Geo\AddressManager;
use CannyDain\Shorty\Helpers\Forms\Models\SingleSelectField;
use CannyDain\Shorty\Helpers\Forms\Models\SubmitButton;
use CannyDain\Shorty\Helpers\Forms\Models\TextboxField;
use CannyDain\Shorty\Views\ShortyFormView;
use CannyDain\Shorty\Views\ShortyView;
use CannyDain\ShortyModules\Invoice\DataLayer\InvoiceDatasource;
use CannyDain\ShortyModules\Invoice\Models\Invoice;

class InvoiceListView extends ShortyFormView implements AddressManagerConsumer
{
    /**
     * @param \CannyDain\Lib\Routing\Models\Route $viewInvoiceRoute
     */
    public function setViewInvoiceRoute($viewInvoiceRoute)
    {
        $this->_viewInvoiceRoute = $viewInvoiceRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getViewInvoiceRoute()
    {
        return $this->_viewInvoiceRoute;
    }
    const FIELD_FILTER_STATUS = 'status';
    const FIELD_FILTER_START = 'start';
    const FIELD_FILTER_END = 'end';

    /**
     * @var Route
     */
    protected $_viewListRoute;
    protected $_statusFilter = InvoiceInterface::STATUS_TO_BE_INVOICED;
    protected $_startDateFilter = null;
    protected $_endDateFilter = null;
    /**
     * @var AddressManager
     */
    protected $_addressManager;
    /**
     * @var InvoiceDatasource
     */
    protected $_datasource;

    /**
     * @var InvoiceInterface[]
     */
    protected $_invoices = array();

    /**
     * @var Route
     */
    protected $_viewInvoiceRoute;

    public function __construct()
    {
        $this->_startDateFilter = strtotime("-1 month");
        $this->_endDateFilter = strtotime("today");
    }


    public function display()
    {
        $this->_displayFilter();
        $this->_displayInvoices();
    }

    protected function _displayFilter()
    {
        $this->_setupForm();
        echo '<div class="filter">';
            $this->_formHelper->displayForm();
        echo '</div>';
    }

    protected function _displayInvoices()
    {
        if (count($this->_invoices) == 0)
            return;

        echo '<table>';
            echo '<tr>';
                echo '<th>Customer</th>';
                echo '<th>Status</th>';
                echo '<th>Total</th>';
            echo '</tr>';

            foreach($this->_invoices as $invoice)
                $this->_displayInvoice($invoice);
        echo '</table>';
    }

    protected function _displayInvoice(InvoiceInterface $invoice)
    {
        $total = 0;
        foreach ($this->_datasource->getInvoiceItemsByInvoiceID($invoice->getID()) as $item)
            $total += (($item->getQuantity() * $item->getPricePerUnitInPence()) - $item->getLineDiscountInPence());

        $address = $this->_addressManager->getAddressByID($invoice->getBillingAddress());
        $addressLine = 'Unknown';
        if ($address != null)
            $addressLine = $address->getName();

        $status = 'unknown';

        switch($invoice->getStatus())
        {
            case InvoiceInterface::STATUS_TO_BE_INVOICED:
                $status = 'To be invoiced';
                break;
            case InvoiceInterface::STATUS_INVOICED:
                $status = 'Awaiting Payment';
                break;
            case InvoiceInterface::STATUS_PAID:
                $status = 'Paid';
                break;
            case InvoiceInterface::STATUS_CANCELLED:
                $status = 'Cancelled';
                break;
        }
        echo '<tr>';
            echo '<td>'.$addressLine.'</td>';
            echo '<td>'.$status.'</td>';
            echo '<td>&pound;'.number_format($total / 100, 2).'</td>';
            echo '<td>';
                $this->_displayInvoiceActions($invoice);
            echo '</td>';
        echo '</tr>';
    }

    protected function _displayInvoiceActions(InvoiceInterface $invoice)
    {
        $uri = $this->_router->getURI($this->_viewInvoiceRoute->getRouteWithReplacements(array('#id#' => $invoice->getID())));

        echo '<a href="'.$uri.'">';
            echo 'View';
        echo '</a>';
    }

    /**
     * @return bool
     */
    public function updateFromPostAndReturnTrueIfPostedAndValid()
    {
        $this->_statusFilter = $this->_request->getParameterOrDefault(self::FIELD_FILTER_STATUS, $this->_statusFilter);
        $start = $this->_request->getParameterOrDefault(self::FIELD_FILTER_START, null);
        $end = $this->_request->getParameterOrDefault(self::FIELD_FILTER_END, null);

        if ($start == null)
            $this->_startDateFilter = null;
        else
            $this->_startDateFilter = strtotime($start);

        if ($end == null)
            $this->_endDateFilter = null;
        else
            $this->_endDateFilter = strtotime($end);

        return true;
    }

    protected function _setupForm()
    {
        if ($this->_formHelper->getField(self::FIELD_FILTER_START) != null)
            return;

        $this->_formHelper->setURI($this->_router->getURI($this->_viewListRoute));
        $this->_formHelper->addField(new SingleSelectField('Invoice Status', self::FIELD_FILTER_STATUS, $this->_statusFilter, array
        (
            InvoiceInterface::STATUS_TO_BE_INVOICED => 'To be Invoiced',
            InvoiceInterface::STATUS_INVOICED => 'Invoice Sent',
            InvoiceInterface::STATUS_PAID => 'Paid',
            InvoiceInterface::STATUS_CANCELLED => 'Cancelled',
        ), 'The invoice status you wish to view'));

        $this->_formHelper->addField(new TextboxField('From Date', self::FIELD_FILTER_START, $this->_startDateFilter, 'The date you wish to view invoices from'));
        $this->_formHelper->addField(new TextboxField('To Date', self::FIELD_FILTER_END, $this->_endDateFilter, 'The date you wish to view invoices to'));
        $this->_formHelper->addField(new SubmitButton('Filter'));
    }

    /**
     * @param \CannyDain\ShortyModules\Invoice\DataLayer\InvoiceDatasource $datasource
     */
    public function setDatasource($datasource)
    {
        $this->_datasource = $datasource;
    }

    /**
     * @return \CannyDain\ShortyModules\Invoice\DataLayer\InvoiceDatasource
     */
    public function getDatasource()
    {
        return $this->_datasource;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $viewListRoute
     */
    public function setViewListRoute($viewListRoute)
    {
        $this->_viewListRoute = $viewListRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getViewListRoute()
    {
        return $this->_viewListRoute;
    }

    public function setEndDateFilter($endDateFilter)
    {
        $this->_endDateFilter = $endDateFilter;
    }

    public function getEndDateFilter()
    {
        return $this->_endDateFilter;
    }

    public function setInvoices($invoices)
    {
        $this->_invoices = $invoices;
    }

    public function getInvoices()
    {
        return $this->_invoices;
    }

    public function setStartDateFilter($startDateFilter)
    {
        $this->_startDateFilter = $startDateFilter;
    }

    public function getStartDateFilter()
    {
        return $this->_startDateFilter;
    }

    public function setStatusFilter($statusFilter)
    {
        $this->_statusFilter = $statusFilter;
    }

    public function getStatusFilter()
    {
        return $this->_statusFilter;
    }

    public function consumeAddressManager(AddressManager $manager)
    {
        $this->_addressManager = $manager;
    }
}