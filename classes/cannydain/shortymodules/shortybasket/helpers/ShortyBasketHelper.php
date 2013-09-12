<?php

namespace CannyDain\ShortyModules\ShortyBasket\Helpers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\ECommerce\Basket\BasketHelperInterface;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\ShortyBasket\Controllers\BasketController;
use CannyDain\ShortyModules\ShortyBasket\DataLayer\ShortyBasketDatalayer;
use CannyDain\ShortyModules\ShortyBasket\Models\BasketModel;
use CannyDain\ShortyModules\ShortyBasket\ShortyBasketModule;

class ShortyBasketHelper implements BasketHelperInterface, ModuleConsumer, SessionConsumer
{
    const SESSION_KEY_BASKET_ID = 'shortbasket.basketid';

    /**
     * @var ShortyBasketDatalayer
     */
    protected $_datasource;

    /**
     * @var SessionHelper
     */
    protected $_session;

    /**
     * @var Route
     */
    protected $_viewBasketRoute;

    /**
     * @return Route
     */
    public function getViewBasketRoute()
    {
        if ($this->_viewBasketRoute == null)
            $this->_viewBasketRoute = new Route(BasketController::BASKET_CONTROLLER_CLASS_NAME);

        return $this->_viewBasketRoute;
    }

    public function setViewBasketRoute(Route $route)
    {
        $this->_viewBasketRoute = $route;
    }


    /**
     * @return array
     */
    public function getProductGUIDs()
    {
        $ret = array();

        foreach ($this->_datasource->getBasketItems($this->getBasketID()) as $item)
        {
            $ret[] = $item->getProductGUID();
        }

        return $ret;
    }

    protected function _getItem($guid)
    {
        foreach ($this->_datasource->getBasketItems($this->getBasketID()) as $item)
        {
            if ($item->getProductGUID() == $guid)
                return $item;
        }

        return null;
    }

    protected function _getOrCreateItem($guid)
    {
        $item = $this->_getItem($guid);
        if ($item == null)
        {
            $item = $this->_datasource->createBasketItem();
            $item->setBasketID($this->getBasketID());
            $item->setProductGUID($guid);
        }

        return $item;
    }

    public function emptyBasket()
    {
        $this->_datasource->deleteBasket($this->getBasketID());
        $this->_session->setData(self::SESSION_KEY_BASKET_ID, null);
    }


    /**
     * @param string $guid
     * @return int
     */
    public function getQuantityOfGUID($guid)
    {
        $item = $this->_getItem($guid);
        if ($item == null)
            return 0;

        return $item->getQuantity();
    }

    /**
     * @param string $guid
     * @param int $qty
     * @return void
     */
    public function setQuantityOfGUID($guid, $qty)
    {
        $item = $this->_getOrCreateItem($guid);
        $item->setQuantity($qty);
        $item->save();
    }

    /**
     * @return int
     */
    public function getBasketID()
    {
        $basketID = $this->_session->getData(self::SESSION_KEY_BASKET_ID);
        if ($basketID != null)
        {
            /**
             * @var BasketModel $basket
             */
            $basket = $this->_datasource->loadBasket($basketID);
            if ($basket == null || $basket->getId() < 1)
                $basketID = null;
        }

        if ($basketID == null)
        {
            $basket = $this->_datasource->createBasket();
            $basket->save();

            $basketID = $basket->getId();
            $this->_session->setData(self::SESSION_KEY_BASKET_ID, $basketID);
        }

        return $basketID;
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        /**
         * @var ShortyBasketModule $module
         */
        $module = $manager->getModuleByClassname(ShortyBasketModule::SHORTY_BASKET_MODULE_NAME);

        $this->_datasource = $module->getDatasource();
    }

    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }
}