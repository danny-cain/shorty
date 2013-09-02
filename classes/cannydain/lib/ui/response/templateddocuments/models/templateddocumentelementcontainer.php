<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Models;

use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplateDocumentElementContainerInterface;
use CannyDain\Lib\UI\Views\ViewInterface;

abstract class TemplatedDocumentElementContainer extends TemplatedDocumentElement implements TemplateDocumentElementContainerInterface
{
    /**
     * @var TemplatedDocumentElement[]
     */
    protected $_children = array();

    public function addChild(TemplatedDocumentElement $child)
    {
        $this->_children[] = $child;
    }

    protected abstract function _drawHead(ViewInterface $view);
    protected abstract function _drawFoot(ViewInterface $view);

    public function display(ViewInterface $view)
    {
        $this->_drawHead($view);
            foreach ($this->_children as $child)
                $child->display($view);
        $this->_drawFoot($view);
    }
}
