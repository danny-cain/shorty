<?php

namespace CannyDain\Shorty\ECommerce;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\UI\Response\Response;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\ResponseConsumer;
use CannyDain\Shorty\ECommerce\DataAccess\ShortyECommerceDataAccess;
use CannyDain\Shorty\ECommerce\Managers\DiscountManager;
use CannyDain\Shorty\ECommerce\Managers\ShippingManager;
use CannyDain\Shorty\ECommerce\Models\ShortyBasketItemModel;
use CannyDain\Shorty\ECommerce\Models\ShortyBasketModel;
use CannyDain\Shorty\ECommerce\PaymentProviders\PaymentProviderInterface;
use CannyDain\Shorty\ECommerce\Providers\ProductProvider;

class ECommerceManager implements DependencyConsumer, RequestConsumer, ResponseConsumer
{
    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var Response
     */
    protected $_response;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var ShortyBasketModel
     */
    protected $_basket;

    /**
     * @var PaymentProviderInterface[]
     */
    protected $_paymentProviders = array();

    /**
     * @var ShippingManager
     */
    protected $_shippingManager;

    /**
     * @var DiscountManager
     */
    protected $_discountManager;

    /**
     * @var ProductProvider
     */
    protected $_productProvider;

    public function initialise()
    {
        $this->datasource()->registerObjects();
    }

    public function addPaymentProvider(PaymentProviderInterface $provider)
    {
        $this->_paymentProviders[] = $provider;
    }

    public function getPaymentProviders() { return $this->_paymentProviders; }

    public function saveBasket()
    {
        $basket = $this->getCurrentBasket();
        if ($basket == null)
            return;

        $this->datasource()->saveBasket($basket);
        $this->datasource()->deleteItemsByBasket($basket->getId());

        foreach ($basket->getItems() as $item)
        {
            $item->setBasketID($basket->getId());
            $this->datasource()->saveItem($item);
        }
    }

    /**
     * @param \CannyDain\Shorty\ECommerce\Managers\ShippingManager $shippingManager
     */
    public function setShippingManager($shippingManager)
    {
        $this->_shippingManager = $shippingManager;
    }

    /**
     * @return \CannyDain\Shorty\ECommerce\Managers\ShippingManager
     */
    public function getShippingManager()
    {
        if ($this->_shippingManager == null)
            $this->_shippingManager = new ShippingManager();

        return $this->_shippingManager;
    }

    /**
     * @param \CannyDain\Shorty\ECommerce\Managers\DiscountManager $discountManager
     */
    public function setDiscountManager($discountManager)
    {
        $this->_discountManager = $discountManager;
    }

    /**
     * @return \CannyDain\Shorty\ECommerce\Managers\DiscountManager
     */
    public function getDiscountManager()
    {
        if ($this->_discountManager == null)
            $this->_discountManager = new DiscountManager();

        return $this->_discountManager;
    }

    public function addToBasket($productGUID, $qty)
    {
        $item = $this->getCurrentBasketAndCreateIfOneDoesntExist()->findItemByGUID($productGUID);
        if ($item == null)
        {
            $item = new ShortyBasketItemModel();
            $this->getCurrentBasket()->addItem($item);
        }

        $item->setProductGUID($productGUID);
        $item->setQty($item->getQty() + $qty);
        $this->updateBasket();
    }

    /**
     * @return ShortyBasketModel
     */
    public function getCurrentBasket()
    {
        if ($this->_basket == null)
            $this->_basket = $this->__basketFactory(false);

        return $this->_basket;
    }

    /**
     * @return ShortyBasketModel
     */
    public function getCurrentBasketAndCreateIfOneDoesntExist()
    {
        if ($this->_basket == null)
            $this->_basket = $this->__basketFactory(true);

        return $this->_basket;
    }

    /**
     * @return ProductProvider
     */
    public function getProductProvider()
    {
        return $this->_productProvider;
    }

    /**
     * @param \CannyDain\Shorty\ECommerce\Providers\ProductProvider $productProvider
     */
    public function setProductProvider($productProvider)
    {
        $this->_productProvider = $productProvider;
    }

    protected function __basketFactory($createIfNoBasketExists = false)
    {
        $basketID = $this->_request->getCookie('basket');
        if ($basketID < 1 && !$createIfNoBasketExists)
            return null;

        $basket = $this->datasource()->getBasketByID($basketID);
        if ($basket == null || $basket->getId() == 0)
        {
            $basket = new ShortyBasketModel();
            $this->datasource()->saveBasket($basket);
            $this->_response->setCookie('basket', $basket->getId());
        }

        return $basket;
    }

    public function deleteCurrentBasket()
    {
        if ($this->getCurrentBasket() == null)
            return;

        $this->datasource()->deleteBasket($this->getCurrentBasket()->getId());
        $this->_basket = null;
        $this->_response->setCookie('basket', 0);
    }

    public function updateBasket()
    {
        $this->getCurrentBasket()->updateBasket($this->getProductProvider(), $this->getShippingManager(), $this->getDiscountManager());
    }

    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new ShortyECommerceDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }

    public function consumeResponse(Response $dependency)
    {
        $this->_response = $dependency;
    }
}