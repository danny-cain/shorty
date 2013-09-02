<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Elements\HTML;

class BlockLevelElement extends BaseHTMLContainerElement
{
    public function __construct($id = '', $classes = array())
    {
        parent::__construct($id, $classes);
    }

    /**
     * @return string
     */
    protected function _getTagName()
    {
        return 'div';
    }
}