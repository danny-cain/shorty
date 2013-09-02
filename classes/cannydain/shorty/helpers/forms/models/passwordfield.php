<?php

namespace CannyDain\Shorty\Helpers\Forms\Models;

use CannyDain\Lib\Web\Server\Request;

class PasswordField extends FieldModel
{
    public function __construct($caption = '', $name = '', $helpText = '', $errorText = '')
    {
        parent::__construct($caption, $name, '', $helpText, $errorText);
    }

    public function getFieldMarkup()
    {
        return '<input type="password" name="'.$this->_name.'" />';
    }

    public function updateFromRequest(Request $request)
    {
        $this->_value = $request->getParameter($this->_name);
    }
}