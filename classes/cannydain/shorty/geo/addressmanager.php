<?php

namespace CannyDain\Shorty\Geo;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Geo\Models\Address;
use CannyDain\Shorty\Geo\Views\SelectAddressView;
use CannyDain\Shorty\Geo\Views\ViewAddressView;

class AddressManager implements DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @param $addressID
     * @return ViewAddressView
     */
    public function getViewAddressView($addressID)
    {
        $view = new ViewAddressView();
        $view->setAddress(new Address());

        return $view;
    }

    public function getSelectAddressView($selectRoute, $userID = null)
    {
        $view = new SelectAddressView();

        $address = new Address();
        $address->setUserID(0);

        $view->setSelectRoute($selectRoute);
        $view->setSelectedAddress($address);

        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    public function saveAddress(Address $address)
    {

    }

    /**
     * @param $userID
     * @return Address[]
     */
    public function getAddressByUser($userID)
    {
        return array();
    }

    /**
     * @param $id
     * @return Address
     */
    public function getAddressByID($id)
    {
        return null;
    }

    public function consumeDependencies(DependencyInjector $dependencies)
    {
        $this->_dependencies = $dependencies;
    }
}