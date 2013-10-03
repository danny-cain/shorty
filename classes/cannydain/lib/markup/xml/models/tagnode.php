<?php

namespace CannyDain\Lib\Markup\XML\Models;

class TagNode extends XMLNode
{
    protected $_namespace = '';
    protected $_name = '';
    /**
     * @var Attribute[]
     */
    protected $_attributes = array();

    /**
     * @var XMLNode[]
     */
    protected $_children = array();

    public function __construct($tagName, $attributes = array())
    {
        foreach ($attributes as $attrName => $val)
        {
            $this->_attributes[] = new Attribute($attrName, $val);
        }

        $namespaceSep = strpos($tagName, ':');
        if ($namespaceSep === false)
        {
            $this->_name = $tagName;
            return;
        }

        $this->_namespace = substr($tagName, 0, $namespaceSep);
        $this->_name = substr($tagName, $namespaceSep + 1);
    }

    public function getAttributeValue($name, $namespace = '')
    {
        foreach ($this->_attributes as $attr)
        {
            if ($attr->getNamespace() != $namespace)
                continue;

            if ($attr->getName() != $name)
                continue;

            return $attr->getVal();
        }

        return '';
    }

    public function getElementsByTagName($tagname, $namespace = '')
    {
        $ret = array();

        foreach ($this->_children as $child)
        {
            if (!($child instanceof TagNode))
                continue;

            if ($child->getNamespace() == $namespace && $child->getName() == $tagname)
                $ret[] = $child;

            $ret = array_merge($ret, $child->getElementsByTagName($tagname, $namespace));
        }

        return $ret;
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

    public function addChild(XMLNode $child) { $this->_children[] = $child; }
    public function getChildren() { return $this->_children; }
    public function getAttributes() { return $this->_attributes; }
    public function addAttribute(Attribute $attr) { $this->_attributes[] = $attr; }
}