<?php

namespace CannyDain\Shorty\ECommerce\Basket;

use CannyDain\Lib\Routing\Models\Route;

class BasketHelper implements BasketHelperInterface
{
    /**
     * @var Route
     */
    protected $_viewBasketRoute;

    /**
     * @return array
     */
    public function getProductGUIDs()
    {
        return array();
    }

    public function setBillingAddress($id)
    {

    }

    public function getBillingAddress()
    {
        return 0;
    }

    public function setDeliveryAddress($id)
    {

    }

    public function getDeliveryAddress()
    {
        return 0;
    }


    /**
     * @param string $guid
     * @return int
     */
    public function getQuantityOfGUID($guid)
    {
        return 0;
    }

    public function emptyBasket()
    {

    }


    public function setViewBasketRoute(Route $route)
    {
        $this->_viewBasketRoute = $route;
    }

    /**
     * @return Route
     */
    public function getViewBasketRoute()
    {
        return $this->_viewBasketRoute;
    }

    /**
     * @param string $guid
     * @param int $qty
     * @return void
     */
    public function setQuantityOfGUID($guid, $qty)
    {

    }

    /**
     * @return int
     */
    public function getBasketID()
    {
        return 0;
    }
}