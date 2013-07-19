<?php

namespace CannyDain\ShortyCoreModules\ShortyNavigation\Models;

class NavItemModel
{
    protected $_id = 0;
    protected $_parent = 0;
    protected $_orderIndex = 0;

    protected $_caption = '';
    protected $_uri = '';
    protected $_rawContent = '';

    public function setCaption($caption)
    {
        $this->_caption = $caption;
    }

    public function getCaption()
    {
        return $this->_caption;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setOrderIndex($orderIndex)
    {
        $this->_orderIndex = $orderIndex;
    }

    public function getOrderIndex()
    {
        return $this->_orderIndex;
    }

    public function setParent($parent)
    {
        $this->_parent = $parent;
    }

    public function getParent()
    {
        return $this->_parent;
    }

    public function setRawContent($rawContent)
    {
        $this->_rawContent = $rawContent;
    }

    public function getRawContent()
    {
        return $this->_rawContent;
    }

    public function setUri($uri)
    {
        $this->_uri = $uri;
    }

    public function getUri()
    {
        return $this->_uri;
    }
}