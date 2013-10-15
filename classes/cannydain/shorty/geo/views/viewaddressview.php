<?php

namespace CannyDain\Shorty\Geo\Views;

use CannyDain\Shorty\Geo\Models\Address;
use CannyDain\Shorty\Views\ShortyView;

class ViewAddressView extends ShortyView
{
    /**
     * @var Address
     */
    protected $_address;
    protected $_title = '';

    public function display()
    {
        $lines = explode("\r\n", $this->_address->getFullAddress("\r\n"));
        echo '<div class="address">';
            echo '<div><strong>'.$this->_title.'</strong></div>';
            foreach ($lines as $line)
            {
                echo '<div>'.$line.'</div>';
            }
        echo '</div>';
    }

    /**
     * @param \CannyDain\Shorty\Geo\Models\Address $address
     */
    public function setAddress($address)
    {
        $this->_address = $address;
    }

    /**
     * @return \CannyDain\Shorty\Geo\Models\Address
     */
    public function getAddress()
    {
        return $this->_address;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getTitle()
    {
        return $this->_title;
    }
}