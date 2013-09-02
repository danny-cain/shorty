<?php

namespace CannyDain\Lib\Forms\Views\HTML;

use CannyDain\Lib\Forms\Models\InputField;
use CannyDain\Lib\Forms\Models\MultipleInputField;
use CannyDain\Lib\Forms\Views\InputFieldView;
use Exception;

abstract class HTMLMultiInputView implements InputFieldView
{
    protected $_maxIndividualElements;
    /**
     * @var MultipleInputField
     */
    protected $_field;

    function __construct(MultipleInputField $field, $maxIndividualElements = 5)
    {
        $this->_maxIndividualElements = $maxIndividualElements;
        $this->_field = $field;

        if (!($this->_field instanceof MultipleInputField))
            throw new Exception("Invalid Input Field Type");
    }

    public function display()
    {
        if (count($this->_field->getOptions()) > $this->_maxIndividualElements)
            $this->_displaySelect();
        else
            $this->_displayIndividualElements();
    }

    protected abstract function _displayIndividualElements();
    protected abstract function _displaySelect();
}