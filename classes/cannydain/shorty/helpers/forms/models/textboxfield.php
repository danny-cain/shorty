<?php

namespace CannyDain\Shorty\Helpers\Forms\Models;

use CannyDain\Lib\Web\Server\Request;

class TextboxField extends FieldModel
{
    public function getFieldMarkup()
    {
        return '<input type="text" name="'.$this->_name.'" value="'.htmlentities($this->_value, ENT_QUOTES, 'UTF-8').'" />';
    }

    public function updateFromRequest(Request $request)
    {
        $this->_value = $request->getParameter($this->_name);
    }
}