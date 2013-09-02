<?php

namespace CannyDain\Lib\Forms\Views\HTML;

use CannyDain\Lib\Web\Server\Request;

class SingleSelectView extends HTMLMultiInputView
{
    protected function _displayIndividualElements()
    {
        echo '<div class="formRow">';
            echo '<span class="input">';
                foreach ($this->_field->getOptions() as $val => $caption)
                {
                    $selected = '';
                    if ($val == $this->_field->getValue())
                        $selected = ' checked="checked"';

                    echo '<span class="formOption">';
                        echo '<input type="radio" name="'.$this->_field->getName().'" value="'.$val.'" '.$selected.' />';
                        echo $caption;
                    echo '</span>';
                }
            echo '</span>';
        echo '</div>';
    }

    public function updateModel(Request $request)
    {
        $this->_field->setValue($request->getParameter($this->_field->getName()));
    }

    protected function _displaySelect()
    {
        echo '<div class="formRow">';
            echo '<span class="caption">';
                echo $this->_field->getCaption();
            echo '</span>';

            echo '<span class="input">';
                echo '<select name="'.$this->_field->getName().'">';
                    foreach ($this->_field->getOptions() as $val => $caption)
                    {
                        $selected = '';

                        if ($val == $this->_field->getValue())
                            $selected = ' selected="selected"';

                        echo '<option value="'.$val.'"'.$selected.'>'.$caption.'</option>';
                    }
                echo '</select>';
            echo '</span>';
        echo '</div>';
    }
}