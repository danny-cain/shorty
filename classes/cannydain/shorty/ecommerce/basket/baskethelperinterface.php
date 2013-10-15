<?php

namespace CannyDain\Shorty\ECommerce\Basket;

use CannyDain\Lib\Routing\Models\Route;

interface BasketHelperInterface
{
    public function setBillingAddress($id);
    public function getBillingAddress();

    public function setDeliveryAddress($id);
    public function getDeliveryAddress();

    /**
     * @return array
     */
    public function getProductGUIDs();

    public function emptyBasket();

    /**
     * @param string $guid
     * @return int
     */
    public function getQuantityOfGUID($guid);

    /**
     * @return Route
     */
    public function getViewBasketRoute();

    public function setViewBasketRoute(Route $route);

    /**
     * @param string $guid
     * @param int $qty
     * @return void
     */
    public function setQuantityOfGUID($guid, $qty);

    /**
     * @return int
     */
    public function getBasketID();
}