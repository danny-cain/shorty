<?php

namespace CannyDain\Lib\Forms\Factories;

use CannyDain\Lib\Forms\Models\InputField;
use CannyDain\Lib\Forms\Models\MultipleInputField;
use CannyDain\Lib\Forms\Views\HTML\EmailFieldView;
use CannyDain\Lib\Forms\Views\HTML\MultiSelectView;
use CannyDain\Lib\Forms\Views\HTML\SingleSelectView;
use CannyDain\Lib\Forms\Views\HTML\TextFieldView;
use CannyDain\Lib\Forms\Views\InputFieldView;

class HTMLInputFieldViewFactory implements InputFieldViewFactory
{
    /**
     * @param InputField $field
     * @return InputFieldView
     */
    public function getView(InputField $field)
    {
        switch($field->getType())
        {
            case InputField::TYPE_TEXT:
                return new TextFieldView($field);
                break;
            case InputField::TYPE_EMAIL:
                return new EmailFieldView($field);
                break;
            case MultipleInputField::TYPE_SINGLE_SELECT:
                return new SingleSelectView($field);
                break;
            case MultipleInputField::TYPE_MULTI_SELECT:
                return new MultiSelectView($field);
                break;
        }

        return null;
    }
}