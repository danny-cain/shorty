<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Elements\HTML;

use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElement;
use CannyDain\Lib\UI\Views\ViewInterface;

abstract class BaseHTMLElement extends TemplatedDocumentElement
{
    protected $_id = '';
    protected $_classes = array();

    public function __construct($id = '', $classes = array())
    {
        $this->_id = $id;
        $this->_classes = $classes;
    }

    /**
     * @return string
     */
    protected abstract function _getTagName();

    /**
     * @return array
     */
    protected function _getAttributes()
    {
        $ret = array();

        if ($this->_id != '')
            $ret[] = 'id="'.$this->_id.'"';

        if (count($this->_classes) > 0)
            $ret[] = 'class="'.implode(' ', $this->_classes).'"';

        return $ret;
    }

    protected function _getAttributeString()
    {
        $attr = $this->_getAttributes();
        if (count($attr) == 0)
            return '';

        return ' '.implode(' ', $attr);
    }

    public function display(ViewInterface $view)
    {
        echo '<'.$this->_getTagName().$this->_getAttributeString().' />';
    }
}
