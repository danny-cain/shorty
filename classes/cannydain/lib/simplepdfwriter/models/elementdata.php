<?php

namespace CannyDain\Lib\SimplePDFWriter\Models;

class ElementData
{
    protected $_width = 0;
    protected $_height = 0;
    protected $_content = array();

    public function setContent($content)
    {
        $this->_content = $content;
    }

    public function getContent()
    {
        return $this->_content;
    }

    public function setHeight($height)
    {
        $this->_height = $height;
    }

    public function getHeight()
    {
        return $this->_height;
    }

    public function setWidth($width)
    {
        $this->_width = $width;
    }

    public function getWidth()
    {
        return $this->_width;
    }
}