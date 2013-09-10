<?php

namespace CannyDain\ShortyModules\ShortyBasket\Controllers;

use CannyDain\Shorty\Consumers\BasketHelperConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\ECommerce\Basket\BasketHelperInterface;
use CannyDain\ShortyModules\ShortyBasket\Views\BasketView;

class BasketController extends ShortyController implements BasketHelperConsumer
{
    const BASKET_CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var BasketHelperInterface
     */
    protected $_basket;

    public function Index()
    {
        $view = new BasketView();

        $quantities = array();
        foreach ($this->_basket->getProductGUIDs() as $guid)
        {
            $quantities[$guid] = $this->_basket->getQuantityOfGUID($guid);
        }

        $view->setProductQuantities($quantities);

        return $view;
    }

    public function consumeBasketHelper(BasketHelperInterface $helper)
    {
        $this->_basket = $helper;
    }
}