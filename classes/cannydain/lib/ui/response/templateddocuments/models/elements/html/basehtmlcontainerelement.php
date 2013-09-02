<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Elements\HTML;

use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplateDocumentElementContainerInterface;
use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElement;
use CannyDain\Lib\UI\Views\ViewInterface;


abstract class BaseHTMLContainerElement extends BaseHTMLElement implements TemplateDocumentElementContainerInterface
{
    /**
     * @var TemplatedDocumentElement[]
     */
    protected $_children = array();

    public function display(ViewInterface $view)
    {
        echo '<'.$this->_getTagName().$this->_getAttributeString().'>';
            foreach ($this->_children as $child)
                $child->display($view);
        echo '</'.$this->_getTagName().'>';
    }

    public function addChild(TemplatedDocumentElement $child)
    {
        $this->_children[] = $child;
    }
}
