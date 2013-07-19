<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Models;

use CannyDain\Lib\UI\Views\ViewInterface;

class Template
{
    /**
     * @var TemplatedDocumentElement[]
     */
    protected $_templateNodes = array();

    public function display(ViewInterface $view)
    {
        foreach ($this->_templateNodes as $node)
            $node->display($view);
    }

    public function setTemplateNodes($templateNodes)
    {
        $this->_templateNodes = $templateNodes;
    }
}