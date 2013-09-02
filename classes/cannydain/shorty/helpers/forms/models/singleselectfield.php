<?php

namespace CannyDain\Shorty\Helpers\Forms\Models;

use CannyDain\Lib\Web\Server\Request;

class SingleSelectField extends FieldModel
{
    protected $_options = array();

    public function __construct($caption = '', $name = '', $value = '', $options = array(), $helpText = '', $errorText = '')
    {
        parent::__construct($caption, $name, $value, $helpText, $errorText);
        $this->_options = $options;
    }

    public function setOptions($options)
    {
        $this->_options = $options;
    }

    public function getOptions()
    {
        return $this->_options;
    }

    public function getFieldMarkup()
    {
        ob_start();

        echo '<select name="'.$this->_name.'">';
            foreach ($this->_options as $key => $caption)
            {
                $selected = '';
                if ($this->_value == $key)
                    $selected = ' selected="selected"';

                echo '<option value="'.$key.'"'.$selected.'>'.$caption.'</option>';
            }
        echo '</select>';

        return ob_get_clean();
    }

    public function updateFromRequest(Request $request)
    {
        $this->_value = $request->getParameter($this->_name);
    }
}