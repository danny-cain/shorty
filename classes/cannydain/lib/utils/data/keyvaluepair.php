<?php

namespace CannyDain\Lib\Utils\Data;

class KeyValuePair
{
    protected $_key = '';
    protected $_value = '';

    public function __construct($key = '', $value = '')
    {
        $this->setKey($key);
        $this->setValue($value);
    }

    public function setKey($key)
    {
        $this->_key = $key;
    }

    public function getKey()
    {
        return $this->_key;
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