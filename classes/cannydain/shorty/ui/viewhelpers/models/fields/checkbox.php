<?php

namespace CannyDain\Shorty\UI\ViewHelpers\Models\Fields;

use CannyDain\Shorty\UI\ViewHelpers\Models\FieldDefinition;

class Checkbox extends FieldDefinition
{
    protected $_checked = false;

    public function getFieldMarkup()
    {
        $checked = '';
        if ($this->_checked)
            $checked = ' checked="checked"';

        return '<input type="checkbox" name="'.$this->_fieldName.'" value="'.$this->_value.'"'.$checked.' />';
    }

    public function setChecked($checked)
    {
        $this->_checked = $checked;
    }

    public function getChecked()
    {
        return $this->_checked;
    }
}