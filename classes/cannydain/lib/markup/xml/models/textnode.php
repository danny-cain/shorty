<?php

namespace CannyDain\Lib\Markup\XML\Models;

class TextNode extends XMLNode
{
    protected $_content = '';

    public function __construct($content = '')
    {
        $this->_content = $content;
    }

    public function setContent($content)
    {
        $this->_content = $content;
    }

    public function getContent()
    {
        return $this->_content;
    }
}