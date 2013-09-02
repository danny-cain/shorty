<?php

namespace CannyDain\Shorty\Helpers\Forms\Models;

use CannyDain\Lib\Web\Server\Request;

class LargeTextField extends FieldModel
{
    public function getFieldMarkup()
    {
        return '<textarea name="'.$this->_name.'">'.htmlentities($this->_value, ENT_COMPAT, 'UTF-8').'</textarea>';
    }

    public function updateFromRequest(Request $request)
    {
        $this->_value = $request->getParameter($this->_name);
    }
}