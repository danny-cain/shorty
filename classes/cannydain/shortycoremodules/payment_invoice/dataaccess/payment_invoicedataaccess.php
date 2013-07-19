<?php

namespace CannyDain\ShortyCoreModules\payment_invoice\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\ShortyCoreModules\Payment_Invoice\Models\InvoiceItemModel;
use CannyDain\ShortyCoreModules\Payment_Invoice\Models\InvoiceModel;

class payment_invoiceDataAccess implements DataMapperConsumer
{
    const OBJECT_INVOICE = InvoiceModel::MODEL_CLASS_NAME;
    const OBJECT_INVOICE_ITEM = InvoiceItemModel::MODEL_CLASS_NAME;

    /**
     * @var DataMapper
     */
    protected $_datamapper;

    /**
     * @param int $pageNumber
     * @return InvoiceModel[]
     */
    public function getInvoicesOrderedByDate($pageNumber = 1)
    {
        $recordsPerPage = 25;
        $startRecord = ($pageNumber - 1) * $recordsPerPage;

        return $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_INVOICE, array(), array(), 'placed DESC', $startRecord, $recordsPerPage);
    }

    public function saveInvoice(InvoiceModel $invoice)
    {
        if ($invoice->getDatePlaced() == 0)
            $invoice->setDatePlaced(time());

        $this->_datamapper->saveObject($invoice);
    }

    public function countInvoices()
    {
        return $this->_datamapper->countObjects(self::OBJECT_INVOICE);
    }

    public function saveInvoiceItem(InvoiceItemModel $item)
    {
        $this->_datamapper->saveObject($item);
    }

    public function deleteInvoice($id)
    {
        $this->_datamapper->deleteObject(self::OBJECT_INVOICE, array('id' => $id));
    }

    public function deleteInvoiceItem($id)
    {
        $this->_datamapper->deleteObject(self::OBJECT_INVOICE_ITEM, array('id' => $id));
    }

    public function deleteInvoiceItemsByInvoice($id)
    {
        foreach ($this->getInvoiceItemsbyInvoice($id) as $item)
            $this->deleteInvoiceItem($item->getId());
    }

    /**
     * @param $id
     * @return InvoiceModel
     */
    public function getInvoiceByID($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_INVOICE, array('id' => $id));
    }

    /**
     * @param $id
     * @return InvoiceItemModel[]
     */
    public function getInvoiceItemsbyInvoice($id)
    {
        return $this->_datamapper->getAllObjectsViaEqualityFilter(self::OBJECT_INVOICE_ITEM, array
        (
            'invoice' => $id
        ), 'tax DESC');
    }

    public function registerObjects()
    {
        $file = dirname(dirname(__FILE__)).'/datadictionary/objects.json';
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);

        $this->_datamapper->dataStructureCheckForObject(self::OBJECT_INVOICE);
        $this->_datamapper->dataStructureCheckForObject(self::OBJECT_INVOICE_ITEM);
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }
}