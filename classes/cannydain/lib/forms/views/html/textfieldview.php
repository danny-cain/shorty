<?php

namespace CannyDain\Lib\Forms\Views\HTML;

use CannyDain\Lib\Web\Server\Request;
use CannyDain\Lib\Forms\Models\InputField;
use CannyDain\Lib\Forms\Views\InputFieldView;

class TextFieldView implements InputFieldView
{
    /**
     * @var InputField
     */
    protected $_field;

    public function __construct(InputField $field)
    {
        $this->_field = $field;
    }

    public function display()
    {
        echo '<div class="formRow">';
            echo '<span class="caption">';
                echo $this->_field->getCaption();
            echo '</span>';

            echo '<span class="input">';
                echo '<input type="text" name="'.$this->_field->getName().'" value="'.$this->_field->getValue().'" />';
            echo '</span>';
        echo '</div>';
    }

    public function updateModel(Request $request)
    {
        $this->_field->setValue($request->getParameter($this->_field->getName()));
    }
}