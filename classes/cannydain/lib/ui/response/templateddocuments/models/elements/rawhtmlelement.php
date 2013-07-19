<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Elements;

use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElement;
use CannyDain\Lib\UI\Views\ViewInterface;

class RawHTMLElement extends TemplatedDocumentElement
{
    protected $_html = '';

    public function __construct($html = '')
    {
        $this->_html = $html;
    }

    public function display(ViewInterface $view)
    {
        echo $this->_html;
    }

    public function setHtml($html)
    {
        $this->_html = $html;
    }

    public function getHtml()
    {
        return $this->_html;
    }
}