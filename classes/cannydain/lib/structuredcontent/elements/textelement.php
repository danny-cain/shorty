<?php

namespace CannyDain\Lib\StructuredContent\Elements;

use CannyDain\Lib\StructuredContent\ContentElement;
use CannyDain\Lib\StructuredContent\ContentElementInterface;
use CannyDain\Lib\StructuredContent\Exceptions\StructuredContentException;

class TextElement extends ContentElement
{
    protected $_text = '';

    public function __construct($text = '')
    {
        $this->setFlowType(self::FLOW_TYPE_INLINE);
        $this->setText($text);
    }

    public function setText($text)
    {
        $this->_text = $text;
    }

    public function getText()
    {
        return $this->_text;
    }

    public function addChild(ContentElementInterface $child)
    {
        throw new StructuredContentException();
    }
}