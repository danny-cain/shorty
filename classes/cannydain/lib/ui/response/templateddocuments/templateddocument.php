<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments;

use CannyDain\Lib\UI\Response\Document\DocumentInterface;
use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Template;
use CannyDain\Lib\UI\Views\ViewInterface;

class TemplatedDocument implements DocumentInterface
{
    /**
     * @var Template
     */
    protected $_template;

    public function display(ViewInterface $view = null)
    {
        $this->_template->display($view);
    }

    /**
     * @param \CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Template $template
     */
    public function setTemplate($template)
    {
        $this->_template = $template;
    }

    /**
     * @return \CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Template
     */
    public function getTemplate()
    {
        return $this->_template;
    }
}