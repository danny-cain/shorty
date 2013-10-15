<?php

namespace CannyDain\Shorty\Geo\FormFields;

use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Geo\Models\Address;
use CannyDain\Shorty\Helpers\Forms\Models\FieldModel;

class SelectAddressField extends FieldModel
{
    /**
     * @var Address[]
     */
    protected $_addresses = array();

    public function __construct($caption = '', $name = '', $value = '', $addresses = array())
    {
        parent::__construct($caption, $name, $value, '', '');
        $this->_addresses = $addresses;
    }


    public function getFieldMarkup()
    {
        ob_start();

        foreach ($this->_addresses as $address)
        {
            echo '<div style="display: inline-block; vertical-align: top; width: 19%; padding: 0.25%; margin: 0.25%;">';
                echo $address->getFullAddress("<br>\r\n");
                echo '<br>';
                echo '<input type="radio" name="'.$this->_name.'" value="'.$address->getId().'" />';
            echo '</div>';
        }

        return ob_get_clean();
    }

    public function updateFromRequest(Request $request)
    {
        $this->_value = $request->getParameter($this->_name);
    }
}