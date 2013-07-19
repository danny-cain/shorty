<?php

namespace CannyDain\Shorty\UI\ViewHelpers\Models\Fields;

use CannyDain\Shorty\UI\ViewHelpers\Models\FieldDefinition;

class SelectBox extends FieldDefinition
{
    protected $_options = array();

    public function getFieldMarkup()
    {
        ob_start();

        if ($this->_shouldDisplayAsSelectBox())
            $this->_displayAsSelectBox();
        else
            $this->_displayAsArrayOfInputs();

        return ob_get_clean();
    }

    protected function _displayAsSelectBox()
    {
        echo '<select name="'.$this->_fieldName.'">';
            foreach ($this->_options as $option => $caption)
            {
                $selected = '';
                if ($option == $this->_value)
                    $selected = ' selected="selected"';

                echo '<option value="'.$option.'"'.$selected.'>'.$caption.'</option>';
            }
        echo '</select>';
    }

    protected function _displayAsArrayOfInputs()
    {
        foreach ($this->_options as $option => $caption)
        {
            $checked = '';
            if ($option == $this->_value)
                $checked = ' checked="checked"';

            echo '<div style="display: inline-block; width: 25%; vertical-align: top; ">';
                echo '<input type="radio" name="'.$this->_fieldName.'" value="'.$option.'"'.$checked.' /> '.$caption;
            echo '</div>';
        }
    }

    protected function _shouldDisplayAsSelectBox()
    {
        return count($this->_options) > 5;
    }

    public function setOptions($options)
    {
        $this->_options = $options;
    }

    public function getOptions()
    {
        return $this->_options;
    }
}