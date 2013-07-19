<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Elements\HTML;

class ImageElement extends BaseHTMLElement
{
    protected $_url = '';

    public function __construct($url = '', $id = '', $classes = array())
    {
        parent::__construct($id, $classes);
        $this->_url = $url;
    }

    protected function _getAttributes()
    {
        $ret = parent::_getAttributes();

        if ($this->_url != '')
            $ret[] = 'src="'.$this->_url.'"';

        return $ret;
    }


    /**
     * @return string
     */
    protected function _getTagName()
    {
        return 'img';
    }
}