<?php

namespace CannyDain\Lib\Forms\Models;

class InputField
{
    protected $_type = '';
    protected $_name = '';
    protected $_caption = '';
    protected $_value = null;

    const TYPE_TEXT = 'text';
    const TYPE_EMAIL = 'email';

    public function __construct($name = '', $caption = '', $type = '', $value = null)
    {
        $this->_name = $name;
        $this->_caption = $caption;
        $this->_type = $type;
        $this->_value = $value;
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

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function getType()
    {
        return $this->_type;
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