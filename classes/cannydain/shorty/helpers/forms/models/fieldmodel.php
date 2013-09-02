<?php

namespace CannyDain\Shorty\Helpers\Forms\Models;

use CannyDain\Lib\Web\Server\Request;

abstract class FieldModel
{
    protected $_caption = '';
    protected $_name = '';
    protected $_value = '';
    protected $_helpText = '';
    protected $_errorText = '';

    public function __construct($caption = '', $name = '', $value = '', $helpText = '', $errorText = '')
    {
        $this->_caption = $caption;
        $this->_name = $name;
        $this->_value = $value;
        $this->_helpText = $helpText;
        $this->_errorText = $errorText;
    }

    public abstract function getFieldMarkup();
    public abstract function updateFromRequest(Request $request);

    public function setErrorText($errorText)
    {
        $this->_errorText = $errorText;
    }

    public function getErrorText()
    {
        return $this->_errorText;
    }

    public function setHelpText($helpText)
    {
        $this->_helpText = $helpText;
    }

    public function getHelpText()
    {
        return $this->_helpText;
    }

    public function setCaption($caption)
    {
        $this->_caption = $caption;
    }

    public function getCaption()
    {
        return $this->_caption;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setValue($value)
    {
        $this->_value = $value;
    }

    public function getValue()
    {
        return $this->_value;
    }
}