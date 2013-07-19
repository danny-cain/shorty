<?php

namespace CannyDain\Shorty\UI\ViewHelpers\Models\Fields;

use CannyDain\Shorty\UI\ViewHelpers\Models\FieldDefinition;

class Password extends FieldDefinition
{
    public function getFieldMarkup()
    {
        return '<input type="password" name="'.$this->_fieldName.'" />';
    }
}