<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Elements\HTML;

use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElement;
use CannyDain\Lib\UI\Views\ViewInterface;

class StylesheetElement extends BaseHTMLElement
{
    protected $_path = '';
    protected $_mediaTypes = '';

    public function __construct($stylesheetPath = '', $mediaTypes = '')
    {
        $this->_path = $stylesheetPath;
        $this->_mediaTypes = $mediaTypes;
        parent::__construct('', array());
    }

    protected function _getAttributes()
    {
        $ret = parent::_getAttributes();
        $ret[] = 'rel="stylesheet"';
        $ret[] = 'type="text/css"';
        $ret[] = 'href="'.$this->_path.'"';

        return $ret;
    }


    /**
     * @return string
     */
    protected function _getTagName()
    {
        return 'link';
    }
}