<?php

namespace CannyDain\Lib\Markup\XML\Models;

class Attribute
{
    protected $_namespace = '';
    protected $_name = '';
    protected $_val = '';

    public function __construct($attrName, $val)
    {
        if (substr($val, 0, 1) == '"')
            $val = substr($val, 1, strlen($val) - 2);

        $this->_val = $val;

        $namespaceSep = strpos($attrName, ':');
        if ($namespaceSep === false)
        {
            $this->_name = $attrName;
            return;
        }

        $this->_namespace = substr($attrName, 0, $namespaceSep);
        $this->_name = substr($attrName, $namespaceSep + 1);
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setNamespace($namespace)
    {
        $this->_namespace = $namespace;
    }

    public function getNamespace()
    {
        return $this->_namespace;
    }

    public function setVal($val)
    {
        $this->_val = $val;
    }

    public function getVal()
    {
        return $this->_val;
    }
}