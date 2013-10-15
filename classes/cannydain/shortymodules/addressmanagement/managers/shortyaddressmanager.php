<?php

namespace CannyDain\ShortyModules\AddressManagement\Managers;

use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\Geo\AddressManager;
use CannyDain\Shorty\Geo\Models\Address;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\AddressManagement\AddressManagementModule;
use CannyDain\ShortyModules\AddressManagement\DataLayer\AddressDataSource;

class ShortyAddressManager extends AddressManager implements ModuleConsumer, SessionConsumer
{
    /**
     * @var SessionHelper
     */
    protected $_session;

    /**
     * @var AddressDataSource
     */
    protected $_datasource;

    public function getViewAddressView($addressID)
    {
        $view = parent::getViewAddressView($addressID);

        $view->setAddress($this->_datasource->loadAddress($addressID));

        return $view;
    }


    public function getSelectAddressView($selectRoute, $userID = null)
    {
        $view = parent::getSelectAddressView($selectRoute);

        $view->getSelectedAddress()->setUserID($this->_session->getUserID());

        if ($userID != null)
            $view->setAddresses($this->_datasource->loadAddressesByUser($userID));
        elseif ($this->_session->getUserID() > 0)
            $view->setAddresses($this->_datasource->loadAddressesByUser($this->_session->getUserID()));

        return $view;
    }


    /**
     * @param Address $address
     */
    public function saveAddress(Address $address)
    {
        $this->_datasource->saveAddress($address);
    }

    /**
     * @param $userID
     * @return Address[]
     */
    public function getAddressByUser($userID)
    {
        return $this->_datasource->loadAddressesByUser($userID);
    }

    /**
     * @param $id
     * @return Address
     */
    public function getAddressByID($id)
    {
        return $this->_datasource->loadAddress($id);
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        $module = $manager->getModuleByClassname(AddressManagementModule::MODULE_NAME);
        if ($module == null)
            throw new \Exception("Address module not found.");

        if (!($module instanceof AddressManagementModule))
            throw new \Exception("Address module not found.");

        $this->_datasource = $module->getDatasource();
    }

    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }
}