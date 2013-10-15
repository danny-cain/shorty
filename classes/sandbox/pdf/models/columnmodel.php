<?php

namespace Sandbox\PDF\Models;

class ColumnModel
{
    protected $_header = '';
    protected $_width = 'auto';

    function __construct($header = '', $width = 'auto')
    {
        $this->_header = $header;
        $this->_width = $width;
    }


    public function setHeader($header)
    {
        $this->_header = $header;
    }

    public function getHeader()
    {
        return $this->_header;
    }

    public function setWidth($width)
    {
        $this->_width = $width;
    }

    public function getWidth()
    {
        return $this->_width;
    }
}