<?php

namespace CannyDain\ShortyModules\Invoice\DataLayer;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\ShortyModules\Invoice\Models\Invoice;
use CannyDain\ShortyModules\Invoice\Models\InvoiceItem;

class InvoiceDatasource extends ShortyDatasource
{
    public function registerObjects()
    {
        $file = dirname(__FILE__).'/datadictionary.json';
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);
    }

    public function createInvoice()
    {
        $invoice = new Invoice();
        $this->_dependencies->applyDependencies($invoice);

        return $invoice;
    }

    public function createInvoiceItem()
    {
        $item = new InvoiceItem();
        $this->_dependencies->applyDependencies($item);

        return $item;
    }

    /**
     * @param $id
     * @return Invoice
     */
    public function getInvoiceByID($id)
    {
        return $this->_datamapper->loadObject(Invoice::OBJECT_TYPE_INVOICE, array('id' => $id));
    }

    /**
     * @param $id
     * @return InvoiceItem
     */
    public function getInvoiceItemByID($id)
    {
        return $this->_datamapper->loadObject(InvoiceItem::OBJECT_TYPE_INVOICE_ITEM, array('id' => $id));
    }

    /**
     * @param $id
     * @return InvoiceItem[]
     */
    public function getInvoiceItemsByInvoiceID($id)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(InvoiceItem::OBJECT_TYPE_INVOICE_ITEM, array
        (
            'invoiceID = :invoice'
        ), array
        (
            'invoice' => $id
        ));
    }
}