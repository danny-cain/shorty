<?php

namespace CannyDain\Lib\Forms\Factories;

use CannyDain\Lib\Forms\Models\InputField;
use CannyDain\Lib\Forms\Views\InputFieldView;

interface InputFieldViewFactory
{
    /**
     * @param InputField $field
     * @return InputFieldView
     */
    public function getView(InputField $field);
}