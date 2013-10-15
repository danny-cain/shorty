<?php

namespace CannyDain\Shorty\Geo\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Geo\FormFields\SelectAddressField;
use CannyDain\Shorty\Geo\Models\Address;
use CannyDain\Shorty\Geo\Views\SelectAddressViewInterface;
use CannyDain\Shorty\Helpers\Forms\Models\SubmitButton;
use CannyDain\Shorty\Helpers\Forms\Models\TextboxField;
use CannyDain\Shorty\Views\ShortyFormView;

class SelectAddressView extends ShortyFormView implements SelectAddressViewInterface
{
    /**
     * @var Address[]
     */
    protected $_addresses = array();

    /**
     * @var Route
     */
    protected $_selectRoute;

    protected $_title = '';

    /**
     * @var Address
     */
    protected $_selectedAddress;

    /**
     * @return bool
     */
    public function updateFromPostAndReturnTrueIfPostedAndValid()
    {
        if (!$this->_request->isPost())
            return false;

        $addressID = $this->_request->getParameterOrDefault('address-id', null);
        if ($addressID != null)
        {
            foreach ($this->_addresses as $address)
            {
                if ($address->getId() != $addressID)
                    continue;

                $this->_selectedAddress = $address;
            }
        }
        else
        {
            $this->_selectedAddress->setName($this->_request->getParameter(Address::FIELD_NAME));
            $this->_selectedAddress->setAddress1($this->_request->getParameter(Address::FIELD_ADDRESS1));
            $this->_selectedAddress->setAddress2($this->_request->getParameter(Address::FIELD_ADDRESS2));
            $this->_selectedAddress->setAddress3($this->_request->getParameter(Address::FIELD_ADDRESS3));
            $this->_selectedAddress->setTown($this->_request->getParameter(Address::FIELD_TOWN));
            $this->_selectedAddress->setCounty($this->_request->getParameter(Address::FIELD_COUNTY));
            $this->_selectedAddress->setCountry($this->_request->getParameter(Address::FIELD_COUNTRY));
            $this->_selectedAddress->setPostcode($this->_request->getParameter(Address::FIELD_POSTCODE));
        }

        $errors = array();

        if ($this->_selectedAddress->getName() == '')
            $errors[Address::FIELD_NAME] = 'You must enter your name';

        if ($this->_selectedAddress->getAddress1() == '')
            $errors[Address::FIELD_ADDRESS1] = 'You must enter your address';

        if ($this->_selectedAddress->getTown() == '')
            $errors[Address::FIELD_TOWN] = 'You must enter your town';

        if ($this->_selectedAddress->getCounty() == '')
            $errors[Address::FIELD_COUNTY] = 'You must enter your county';

        if ($this->_selectedAddress->getCountry() == '')
            $errors[Address::FIELD_COUNTRY] = 'You must enter your country';

        if ($this->_selectedAddress->getPostcode() == '')
            $errors[Address::FIELD_POSTCODE] = 'You must enter your postcode';

        foreach ($errors as $field => $error)
            $this->_formHelper->getField($field)->setErrorText($error);


        return count($errors) == 0;
    }

    protected function _setupForm()
    {
        if ($this->_formHelper->getField(Address::FIELD_ADDRESS1) != null)
            return;

        $this->_formHelper->setMethod('POST');
        $this->_formHelper->setURI($this->_router->getURI($this->_selectRoute));
        $this->_formHelper->addField(new SelectAddressField('Select Saved Address', 'address-id', $this->_selectedAddress->getId(), $this->_addresses));
        $this->_formHelper->addField(new TextboxField('Name', Address::FIELD_NAME, $this->_selectedAddress->getName(), 'Your name'));
        $this->_formHelper->addField(new TextboxField('Address', Address::FIELD_ADDRESS1, $this->_selectedAddress->getAddress1(), 'Your address'));
        $this->_formHelper->addField(new TextboxField('', Address::FIELD_ADDRESS2, $this->_selectedAddress->getAddress2(), ''));
        $this->_formHelper->addField(new TextboxField('', Address::FIELD_ADDRESS3, $this->_selectedAddress->getAddress3(), ''));
        $this->_formHelper->addField(new TextboxField('Town', Address::FIELD_TOWN, $this->_selectedAddress->getTown(), 'Your town'));
        $this->_formHelper->addField(new TextboxField('County', Address::FIELD_COUNTY, $this->_selectedAddress->getCounty(), 'Your county /state'));
        $this->_formHelper->addField(new TextboxField('Country', Address::FIELD_COUNTRY, $this->_selectedAddress->getCountry(), 'Your country'));
        $this->_formHelper->addField(new TextboxField('Postcode', Address::FIELD_POSTCODE, $this->_selectedAddress->getPostcode(), 'Your postal/zip code'));

        $this->_formHelper->addField(new SubmitButton('Select Address'));
    }

    public function display()
    {
        $this->_setupForm();

        echo '<h1>'.$this->_title.'</h1>';

        $this->_formHelper->displayForm();
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getTitle()
    {
        return $this->_title;
    }


    public function setAddresses($addresses)
    {
        $this->_addresses = $addresses;
    }

    public function getAddresses()
    {
        return $this->_addresses;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $selectRoute
     */
    public function setSelectRoute($selectRoute)
    {
        $this->_selectRoute = $selectRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getSelectRoute()
    {
        return $this->_selectRoute;
    }

    /**
     * @param \CannyDain\Shorty\Geo\Models\Address $selectedAddress
     */
    public function setSelectedAddress($selectedAddress)
    {
        $this->_selectedAddress = $selectedAddress;
    }

    /**
     * @return \CannyDain\Shorty\Geo\Models\Address
     */
    public function getSelectedAddress()
    {
        return $this->_selectedAddress;
    }
}