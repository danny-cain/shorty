<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Elements\HTML;

class HeadElement extends BaseHTMLContainerElement
{
    public function __construct()
    {
        parent::__construct('', array());
    }

    /**
     * @return string
     */
    protected function _getTagName()
    {
        return 'head';
    }
}