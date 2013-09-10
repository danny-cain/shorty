<?php

namespace CannyDain\ShortyModules\SimpleShop\Views;

use CannyDain\Shorty\Consumers\BasketHelperConsumer;
use CannyDain\Shorty\Consumers\InvoiceManagerConsumer;
use CannyDain\Shorty\Consumers\ProductManagerConsumer;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\ECommerce\Basket\BasketHelperInterface;
use CannyDain\Shorty\ECommerce\Products\ProductManager;
use CannyDain\Shorty\Finance\InvoiceManager;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\Shorty\Views\ShortyView;
use CannyDain\ShortyModules\SimpleShop\Controllers\SimpleShopController;

class MiniBasketView extends ShortyView implements SessionConsumer, BasketHelperConsumer, ProductManagerConsumer
{
    /**
     * @var SessionHelper
     */
    protected $_session;

    /**
     * @var BasketHelperInterface
     */
    protected $_basket;

    /**
     * @var ProductManager
     */
    protected $_productManager;

    public function display()
    {
        echo '<h2>Basket</h2>';

        $guids = $this->_basket->getProductGUIDs();
        foreach ($guids as $guid)
        {
            $item = $this->_productManager->getProductInfo($guid);
            $qty = $this->_basket->getQuantityOfGUID($guid);
            $linePrice = $qty * $item->getPricePerUnitInPence();

            echo '<div>';
                echo $qty.' x '.$item->getName().' (@ &pound; '.number_format($item->getPricePerUnitInPence() / 100, 2).' each) = &pound;'.number_format($linePrice / 100, 2);
            echo '</div>';
        }

        echo '<div>';
            echo count($guids).' items';
        echo '</div>';
    }

    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }

    public function consumeBasketHelper(BasketHelperInterface $helper)
    {
        $this->_basket= $helper;
    }

    public function consumeProductManager(ProductManager $manager)
    {
        $this->_productManager = $manager;
    }
}