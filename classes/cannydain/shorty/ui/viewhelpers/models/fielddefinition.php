<?php

namespace CannyDain\Shorty\UI\ViewHelpers\Models;

use CannyDain\Lib\Web\Server\Request;

class FieldDefinition
{
    protected $_fieldName = '';
    protected $_caption = '';
    protected $_helpText = '';
    protected $_errorMessage = '';
    protected $_value = '';

    public function updateFromRequest(Request $request)
    {
        $this->_value = $request->getParameter($this->_fieldName);
    }

    public function getFieldMarkup()
    {
        return $this->_value;
    }

    public function setValue($value)
    {
        $this->_value = $value;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function setCaption($caption)
    {
        $this->_caption = $caption;
    }

    public function getCaption()
    {
        return $this->_caption;
    }

    public function setErrorMessage($errorMessage)
    {
        $this->_errorMessage = $errorMessage;
    }

    public function getErrorMessage()
    {
        return $this->_errorMessage;
    }

    public function setFieldName($fieldName)
    {
        $this->_fieldName = $fieldName;
    }

    public function getFieldName()
    {
        return $this->_fieldName;
    }

    public function setHelpText($helpText)
    {
        $this->_helpText = $helpText;
    }

    public function getHelpText()
    {
        return $this->_helpText;
    }
}