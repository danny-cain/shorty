<?php

namespace CannyDain\Shorty\Helpers\Forms\Models;

use CannyDain\Lib\Web\Server\Request;

class HiddenField extends FieldModel
{
    public function __construct($name = '', $value = '')
    {
        parent::__construct('', $name, $value);
    }

    public function getFieldMarkup()
    {
        return '<input type="hidden" name="'.htmlentities($this->_name, ENT_QUOTES, 'UTF-8').'" value="'.htmlentities($this->_value, ENT_QUOTES, 'UTF-8').'" />';
    }

    public function updateFromRequest(Request $request)
    {
        $this->_value = $request->getParameter($this->_name);
    }
}