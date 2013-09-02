<?php

namespace CannyDain\Shorty\Config;

use CannyDain\Lib\TypeWrappers\ArrayWrapper;

class Configuration
{
    protected $_config = array();
    protected $_pathSeparator = '.';

    public function setConfiguration($config)
    {
        $this->_config = $config;
    }

    public function getConfiguration()
    {
        return $this->_config;
    }

    public function getValue($path)
    {
        return ArrayWrapper::getFieldFromDeepAssociativeArray($this->_config, explode($this->_pathSeparator, $path));
    }

    public function setValue($path, $val)
    {
        $this->_config = ArrayWrapper::setFieldInDeepAssociativeArray($this->_config, $val, explode($this->_pathSeparator, $path));
    }
}