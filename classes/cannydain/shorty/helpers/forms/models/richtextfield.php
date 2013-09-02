<?php

namespace CannyDain\Shorty\Helpers\Forms\Models;

use CannyDain\Lib\Web\Server\Request;

class RichtextField extends FieldModel
{
    public function getFieldMarkup()
    {
        return '<textarea class="richText" name="'.$this->_name.'">'.htmlentities($this->_value, ENT_COMPAT, 'UTF-8').'</textarea>';
    }

    public function updateFromRequest(Request $request)
    {
        $this->_value = $request->getParameter($this->_name);
    }
}