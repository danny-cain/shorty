<?php

namespace CannyDain\Lib\Markup\XML;

use CannyDain\Lib\Markup\XML\Models\TagNode;
use CannyDain\Lib\Markup\XML\Models\TextNode;

class InMemoryXMLParser extends BaseXMLParser
{
    /**
     * @var TagNode[]
     */
    protected $_stack = array();

    /**
     * @var TagNode
     */
    protected $_currentTag = null;

    /**
     * @var TagNode
     */
    protected $_rootTag = null;

    protected function _notify_startTag($tag, $attributes)
    {
        $newTag = new TagNode($tag, $attributes);
        if ($this->_currentTag == null)
        {
            $this->_currentTag = $newTag;
            $this->_rootTag = $newTag;
            return;
        }

        $this->_currentTag->addChild($newTag);
        $this->_stack[] = $this->_currentTag;
        $this->_currentTag = $newTag;
    }

    protected function _notify_endTag($tag)
    {
        $this->_currentTag = array_pop($this->_stack);
    }

    protected function _notify_textContent($text)
    {
        $this->_currentTag->addChild(new TextNode($text));
    }

    public function getRootNode() { return $this->_rootTag; }

    protected function _reset()
    {
        parent::_reset();
        $this->_stack = array();
        $this->_rootTag = null;
        $this->_currentTag = null;
    }


}