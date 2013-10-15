<?php

namespace Sandbox\PDF\Models;

class CellModel
{
    protected $_contents = '';
    protected $_width = 'auto';
    protected $_lineHeight = 0;
    protected $_border = 0;
    protected $_justification = 'L';

    function __construct($contents = '', $width = 'auto', $lineHeight = 0, $border = 0, $justification = 'L')
    {
        $this->_contents = $contents;
        $this->_width = $width;
        $this->_lineHeight = $lineHeight;
        $this->_border = $border;
        $this->_justification = $justification;
    }

    public function setJustification($justification)
    {
        $this->_justification = $justification;
    }

    public function getJustification()
    {
        return $this->_justification;
    }

    public function setBorder($border)
    {
        $this->_border = $border;
    }

    public function getBorder()
    {
        return $this->_border;
    }

    public function setContents($contents)
    {
        $this->_contents = $contents;
    }

    public function getContents()
    {
        return $this->_contents;
    }

    public function setLineHeight($lineHeight)
    {
        $this->_lineHeight = $lineHeight;
    }

    public function getLineHeight()
    {
        return $this->_lineHeight;
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