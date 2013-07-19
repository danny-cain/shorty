<?php

namespace CannyDain\Shorty\UI\ViewHelpers\Models\Fields;

use CannyDain\Shorty\UI\ViewHelpers\Models\FieldDefinition;

class LargeText extends FieldDefinition
{
    public function getFieldMarkup()
    {
        ob_start();
        echo '<textarea name="'.$this->_fieldName.'">'.htmlentities($this->_value, ENT_COMPAT, 'UTF-8').'</textarea>';
        return ob_get_clean();
    }
}