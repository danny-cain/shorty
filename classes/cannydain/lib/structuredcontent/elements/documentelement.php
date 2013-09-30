<?php

namespace CannyDain\Lib\StructuredContent\Elements;

use CannyDain\Lib\StructuredContent\ContentElement;

class DocumentElement extends ContentElement
{
    public function __construct()
    {
        $this->setFlowType(self::FLOW_TYPE_BLOCK);
    }
}