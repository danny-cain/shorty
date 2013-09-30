<?php

namespace CannyDain\Lib\StructuredContent;

use CannyDain\Lib\Utils\Data\KeyValuePairCollection;

abstract class ContentElement implements ContentElementInterface
{
    /**
     * @var KeyValuePairCollection
     */
    protected $_styles;

    /**
     * @var KeyValuePairCollection
     */
    protected $_attributes;

    /**
     * @var ContentElementInterface[]
     */
    protected $_children = array();

    public function addChild(ContentElementInterface $child)
    {
        $this->_children[] = $child;
    }

    /**
     * @return ContentElementInterface[]
     */
    public function getChildren()
    {
        return $this->_children;
    }

    public function getFlowType()
    {
        return $this->styles()->getValue('display', self::FLOW_TYPE_BLOCK);
    }

    public function setFlowType($type)
    {
        $this->styles()->setValue('display', $type);
    }

    /**
     * @return KeyValuePairCollection
     */
    public function styles()
    {
        if ($this->_styles == null)
            $this->_styles = new KeyValuePairCollection();

        return $this->_styles;
    }

    /**
     * @return KeyValuePairCollection
     */
    public function attributes()
    {
        if ($this->_attributes == null)
            $this->_attributes = new KeyValuePairCollection();

        return $this->_attributes;
    }
}