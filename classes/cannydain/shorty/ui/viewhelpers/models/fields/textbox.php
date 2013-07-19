<?php

namespace CannyDain\Shorty\UI\ViewHelpers\Models\Fields;

use CannyDain\Shorty\UI\ViewHelpers\Models\FieldDefinition;

class Textbox extends FieldDefinition
{
    public function getFieldMarkup()
    {
        return '<input type="text" name="'.htmlentities($this->_fieldName, ENT_COMPAT, 'UTF-8').'" value="'.htmlentities($this->_value, ENT_COMPAT, 'UTF-8').'" />';
    }
}