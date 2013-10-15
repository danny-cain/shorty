<?php

namespace CannyDain\Shorty\Geo\Views;

use CannyDain\Lib\SimplePDFWriter\PDFWriter;
use CannyDain\Lib\SimplePDFWriter\PDFWriter2;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Consumers\AddressManagerConsumer;
use CannyDain\Shorty\Finance\Interfaces\InvoiceInterface;
use CannyDain\Shorty\Finance\Interfaces\InvoiceItemInterface;
use CannyDain\Shorty\Geo\AddressManager;

class InvoicePDFView implements ViewInterface, AddressManagerConsumer
{
    /**
     * @var InvoiceInterface
     */
    protected $_invoice;

    /**
     * @var PDFWriter2
     */
    protected $_writer;

    /**
     * @var InvoiceItemInterface[]
     */
    protected $_items;

    /**
     * @var AddressManager
     */
    protected $_addressManager;

    public function display()
    {
        $this->_writer = new PDFWriter();
        $this->_writer->SetFont('Arial');
        $this->_writer->AddPage();

        $this->_writeAddresses();

        echo $this->_writer->Output('invoice-'.$this->_invoice->getID().'.pdf', 'S');
    }

    protected function _displayItems()
    {
        
    }

    protected function _displayItem(InvoiceItemInterface $item)
    {

    }

    protected function _writeAddresses()
    {
        $billingAddress = $this->_addressManager->getAddressByID($this->_invoice->getBillingAddress());
        $deliveryAddress = $this->_addressManager->getAddressByID($this->_invoice->getDeliveryAddress());

        if ($billingAddress == null)
            $billing = array();
        else
            $billing = explode("\n", $billingAddress->getFullAddress("\n"));

        if ($deliveryAddress == null)
            $delivery = array();
        else
            $delivery = explode("\n", $deliveryAddress->getFullAddress("\n"));

        while (count($billing) < count($delivery))
            $billing[] = "";

        while (count($delivery) < count($billing))
            $delivery[] = "";

        array_unshift($billing, "Billing Address");
        array_unshift($delivery, "Delivery Address");

        $width = floor($this->_writer->w - $this->_writer->lMargin - $this->_writer->rMargin);
        $left = $this->_writer->lMargin;
        $top = $this->_writer->tMargin;
        $halfWidth = $width /2;
        $right = $this->_writer->w - $this->_writer->rMargin;

        $this->_writer->Line($left, $top, $right, $top);
        $this->_writer->SetXY($left, $top + 5);
        $this->_writer->MultiCell($halfWidth, 5, implode("\n", $billing), 0, 'L');
        $this->_writer->SetXY($left + $halfWidth, $top + 5);
        $this->_writer->MultiCell($halfWidth, 5, implode("\n", $delivery), 0, 'L');
        $this->_writer->Line($left, $this->_writer->GetY() + 5, $right, $this->_writer->GetY() + 5);
    }

    public function getContentType()
    {
        return 'application/pdf';
    }

    /**
     * @param \CannyDain\Shorty\Finance\Interfaces\InvoiceInterface $invoice
     */
    public function setInvoice($invoice)
    {
        $this->_invoice = $invoice;
    }

    /**
     * @return \CannyDain\Shorty\Finance\Interfaces\InvoiceInterface
     */
    public function getInvoice()
    {
        return $this->_invoice;
    }

    public function setItems($items)
    {
        $this->_items = $items;
    }

    public function getItems()
    {
        return $this->_items;
    }

    public function consumeAddressManager(AddressManager $manager)
    {
        $this->_addressManager = $manager;
    }
}