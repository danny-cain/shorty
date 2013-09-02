<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Elements\HTML;

class ScriptElement extends BaseHTMLContainerElement
{
    protected $_src = '';

    public function __construct($source = '')
    {
        $this->_src = $source;
        parent::__construct('', array());
    }

    protected function _getAttributes()
    {
        $ret = parent::_getAttributes();
        $ret[] = 'type="text/javascript"';
        $ret[] = 'src="'.$this->_src.'"';

        return $ret;
    }


    /**
     * @return string
     */
    protected function _getTagName()
    {
        return 'script';
    }
}