<?php

namespace CannyDain\Shorty\Geo\Views;

use CannyDain\Lib\UI\Views\ViewInterface;

interface SelectAddressViewInterface extends ViewInterface
{
    /**
     * @return bool
     */
    public function updateFromPostAndReturnTrueIfPostedAndValid();

    /**
     * @param \CannyDain\Shorty\Geo\Models\Address $selectedAddress
     */
    public function setSelectedAddress($selectedAddress);

    /**
     * @return \CannyDain\Shorty\Geo\Models\Address
     */
    public function getSelectedAddress();

    public function setTitle($title);

    public function getTitle();
}