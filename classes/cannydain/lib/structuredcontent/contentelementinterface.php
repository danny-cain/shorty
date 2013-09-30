<?php

namespace CannyDain\Lib\StructuredContent;

use CannyDain\Lib\Utils\Data\KeyValuePairCollection;

interface ContentElementInterface
{
    const FLOW_TYPE_INLINE = 'inline';
    const FLOW_TYPE_INLINE_BLOCK = 'inline-block';
    const FLOW_TYPE_BLOCK = 'block';

    public function getFlowType();

    public function setFlowType($type);

    public function addChild(ContentElementInterface $child);

    /**
     * @return ContentElementInterface[]
     */
    public function getChildren();

    /**
     * @return KeyValuePairCollection
     */
    public function styles();

    /**
     * @return KeyValuePairCollection
     */
    public function attributes();


}