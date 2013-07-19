<?php

namespace CannyDain\ShortyCoreModules\Payment_Invoice\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Utils\Date\DateFormatManager;
use CannyDain\Shorty\Consumers\DateTimeConsumer;
use CannyDain\ShortyCoreModules\Payment_Invoice\Models\InvoiceModel;

class ListInvoicesView extends HTMLView implements DateTimeConsumer
{
    /**
     * @var DateFormatManager
     */
    protected $_dates;

    /**
     * @var InvoiceModel[]
     */
    protected $_invoices;
    protected $_paginationURLTemplate = '';
    protected $_pageNumber = 1;
    protected $_numberOfPages = 1;

    protected $_viewLinkTemplate = '';
    protected $_printLinkTemplate = '';

    public function display()
    {
        echo '<h1>Invoices</h1>';

        $this->_displayPagination();

        foreach ($this->_invoices as $invoice)
            $this->_displayInvoice($invoice);

        $this->_displayPagination();
    }

    protected function _displayPagination()
    {
        $nextPage = strtr($this->_paginationURLTemplate, array('#page#' => $this->_pageNumber - 1));
        $prevPage = strtr($this->_paginationURLTemplate, array('#page#' => $this->_pageNumber + 1));

        $links = array();
        if ($this->_pageNumber > 1)
            $links[] = '<a href="'.$prevPage.'">&laquo; Previous</a>';

        if ($this->_pageNumber < $this->_numberOfPages)
            $links[] = '<a href="'.$nextPage.'">&raquo; Next</a>';

        echo '<div>';
            echo implode(' | ', $links);
        echo '</div>';
    }


    protected function _displayInvoice(InvoiceModel $invoice)
    {
        echo '<div>';
            echo '<div style="height: 24px; display: inline-block; width: 10%; vertical-align: bottom; ">';
                echo $invoice->getName();
            echo '</div>';

            echo '<div style="height: 24px; display: inline-block; width: 10%; vertical-align: bottom; ">';
                echo InvoiceModel::getFriendlyNameForStatus($invoice->getStatus());
            echo '</div>';

            echo '<div style="height: 24px; display: inline-block; width: 20%; vertical-align: bottom;">';
                echo $this->_dates->getFormattedDateTime($invoice->getDatePlaced());
            echo '</div>';

            echo '<div style="height: 24px; display: inline-block; width: 20%; vertical-align: bottom;">';
                echo $invoice->getTown();
            echo '</div>';

            echo '<div style="height: 24px; display: inline-block; width: 20%; vertical-align: bottom;">';
                echo $invoice->getCountry();
            echo '</div>';

            echo '<div style="height: 24px; display: inline-block; width: 20%; vertical-align: bottom;">';
                echo '<a href="'.strtr($this->_viewLinkTemplate, array('#id#' => $invoice->getId())).'">';
                    echo 'View';
                echo '</a>';

                echo ' | ';

                echo '<a target="_blank" href="'.strtr($this->_printLinkTemplate, array('#id#' => $invoice->getId())).'">';
                    echo 'Print';
                echo '</a>';
            echo '</div>';
        echo '</div>';
    }

    public function setNumberOfPages($numberOfPages)
    {
        $this->_numberOfPages = $numberOfPages;
    }

    public function getNumberOfPages()
    {
        return $this->_numberOfPages;
    }

    public function setInvoices($invoices)
    {
        $this->_invoices = $invoices;
    }

    public function getInvoices()
    {
        return $this->_invoices;
    }

    public function setPageNumber($pageNumber)
    {
        $this->_pageNumber = $pageNumber;
    }

    public function getPageNumber()
    {
        return $this->_pageNumber;
    }

    public function setPaginationURLTemplate($paginationURLTemplate)
    {
        $this->_paginationURLTemplate = $paginationURLTemplate;
    }

    public function getPaginationURLTemplate()
    {
        return $this->_paginationURLTemplate;
    }

    public function setPrintLinkTemplate($printLinkTemplate)
    {
        $this->_printLinkTemplate = $printLinkTemplate;
    }

    public function getPrintLinkTemplate()
    {
        return $this->_printLinkTemplate;
    }

    public function setViewLinkTemplate($viewLinkTemplate)
    {
        $this->_viewLinkTemplate = $viewLinkTemplate;
    }

    public function getViewLinkTemplate()
    {
        return $this->_viewLinkTemplate;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDateTimeManager(DateFormatManager $dependency)
    {
        $this->_dates = $dependency;
    }
}