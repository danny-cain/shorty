<?php

namespace CannyDain\ShortyModules\ShortyBasket\Views;

use CannyDain\Shorty\Consumers\ProductManagerConsumer;
use CannyDain\Shorty\ECommerce\Products\ProductManager;
use CannyDain\Shorty\Views\ShortyView;

class BasketView extends ShortyView implements ProductManagerConsumer
{
    /**
     * @var ProductManager
     */
    protected $_productManager;

    protected $_productQuantities = array();

    public function display()
    {
        echo '<h1>Basket</h1>';

        echo '<table>';
        foreach ($this->_productQuantities as $guid => $qty)
        {
            if ($qty < 1)
                continue;

            $this->_displayProduct($guid, $qty);
        }
        echo '</table>';
    }

    protected function _displayProduct($guid, $qty)
    {
        $product = $this->_productManager->getProductInfo($guid);

        if ($product == null)
            return;

        $lineTotal = $product->getPricePerUnitInPence() * $qty;

        echo '<tr>';
            echo '<td>'.$product->getName().'</td>';
            echo '<td>'.$product->getDescription().'</td>';
            echo '<td>'.$qty.'</td>';
            echo '<td>*</td>';
            echo '<td>&pound;'.number_format($product->getPricePerUnitInPence() / 100, 2).'</td>';
            echo '<td>&pound;'.number_format($lineTotal / 100, 2).'</td>';
        echo '</tr>';
    }

    public function setProductQuantities($guidToQtyMap)
    {
        $this->_productQuantities = $guidToQtyMap;
    }

    public function consumeProductManager(ProductManager $manager)
    {
        $this->_productManager = $manager;
    }
}