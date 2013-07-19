<?php

namespace CannyDain\ShortyCoreModules\Payment_Invoice\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\Payment_Invoice\Models\InvoiceModel;

class InvoiceDetailsView extends HTMLView implements FormHelperConsumer
{
    /**
     * @var FormHelper
     */
    protected $_formHelper;

    /**
     * @var InvoiceModel
     */
    protected $_invoice;

    protected $_updateURI = '';

    public function display()
    {
        echo '<h1>Pay by Invoice</h1>';

        $this->_formHelper->startForm($this->_updateURI);
            $this->_formHelper->editText('name', 'Name', $this->_invoice->getName(), 'Your name');
            $this->_formHelper->editText('address1', 'Address', $this->_invoice->getAddress1(), 'Your address');
            $this->_formHelper->editText('address2', '', $this->_invoice->getAddress2(), '');
            $this->_formHelper->editText('address3', '', $this->_invoice->getAddress3(), '');
            $this->_formHelper->editText('town', 'Town', $this->_invoice->getTown(), '');
            $this->_formHelper->editText('county', 'County', $this->_invoice->getCounty(), '');
            $this->_formHelper->editText('country', 'Country', $this->_invoice->getCountry(), '');
            $this->_formHelper->editText('postcode', 'Postcode', $this->_invoice->getPostcode(), '');
            $this->_formHelper->submitButton('Submit');
        $this->_formHelper->endForm();
    }

    public function updateFromRequest(Request $request)
    {
        $this->_invoice->setName($request->getParameter('name'));
        $this->_invoice->setAddress1($request->getParameter('address1'));
        $this->_invoice->setAddress2($request->getParameter('address2'));
        $this->_invoice->setAddress3($request->getParameter('address3'));
        $this->_invoice->setTown($request->getParameter('town'));
        $this->_invoice->setCounty($request->getParameter('county'));
        $this->_invoice->setCountry($request->getParameter('country'));
        $this->_invoice->setPostcode($request->getParameter('postcode'));
    }

    public function setUpdateURI($updateURI)
    {
        $this->_updateURI = $updateURI;
    }

    public function getUpdateURI()
    {
        return $this->_updateURI;
    }

    /**
     * @param \CannyDain\ShortyCoreModules\Payment_Invoice\Models\InvoiceModel $invoice
     */
    public function setInvoice($invoice)
    {
        $this->_invoice = $invoice;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\Payment_Invoice\Models\InvoiceModel
     */
    public function getInvoice()
    {
        return $this->_invoice;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}