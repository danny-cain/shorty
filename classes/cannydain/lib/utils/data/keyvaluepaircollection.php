<?php

namespace CannyDain\Lib\Utils\Data;

class KeyValuePairCollection
{
    /**
     * @var KeyValuePair[]
     */
    protected $_data = array();

    public function setValue($key, $value)
    {
        if (!isset($this->_data[$key]))
            $this->_data[$key] = new KeyValuePair($key, $value);
        else
            $this->_data[$key]->setValue($value);
    }

    public function getValue($key, $default = null)
    {
        if (!isset($this->_data[$key]))
            return $default;

        return $this->_data[$key]->getValue();
    }

    public function getKeys()
    {
        return array_keys($this->_data);
    }
}