<?php

namespace CannyDain\Lib\StructuredContent\Elements;

use CannyDain\Lib\StructuredContent\ContentElement;
use CannyDain\Lib\StructuredContent\ContentElementInterface;
use CannyDain\Lib\StructuredContent\Exceptions\StructuredContentException;

class TableRowElement extends ContentElement
{
    public function __construct()
    {
        $this->setFlowType(self::FLOW_TYPE_BLOCK);
    }

    public function addChild(ContentElementInterface $child)
    {
        if (!($child instanceof TableCellElement))
            throw new StructuredContentException;

        parent::addChild($child);
    }
}