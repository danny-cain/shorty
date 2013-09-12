<?php

namespace CannyDain\Lib\UI\Views;

class PlainTextView implements ViewInterface
{
    protected $_contentType = 'text/plain';
    protected $_content = '';

    public function __construct($content = '', $ct = 'text/plain')
    {
        $this->setContentType($ct);
        $this->setContent($content);
    }

    public function setContent($content) { $this->_content = $content; }
    public function setContentType($ct) { $this->_contentType = $ct; }

    public function getContentType()
    {
        return $this->_contentType;
    }

    public function display()
    {
        echo $this->_content;
    }
}