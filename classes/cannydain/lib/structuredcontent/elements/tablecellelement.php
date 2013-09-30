<?php

namespace CannyDain\Lib\StructuredContent\Elements;

use CannyDain\Lib\StructuredContent\ContentElement;
use CannyDain\Lib\StructuredContent\ContentElementInterface;

class TableCellElement extends ContentElement
{
    public function __construct()
    {
        $this->setFlowType(self::FLOW_TYPE_INLINE_BLOCK);
    }
}