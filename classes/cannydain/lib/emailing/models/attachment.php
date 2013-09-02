<?php

namespace CannyDain\Lib\Emailing\Models;

class Attachment
{
    protected $_filename = '';
    protected $_mimeType = '';
    protected $_sourceFile = '';

    public function __construct($filename = '', $mimeType = '', $sourceFile = '')
    {
        $this->_filename = $filename;
        $this->_mimeType = $mimeType;
        $this->_sourceFile = $sourceFile;
    }

    public function setSourceFile($content)
    {
        $this->_sourceFile = $content;
    }

    public function getSourceFile()
    {
        return $this->_sourceFile;
    }

    public function setFilename($filename)
    {
        $this->_filename = $filename;
    }

    public function getFilename()
    {
        return $this->_filename;
    }

    public function setMimeType($mimeType)
    {
        $this->_mimeType = $mimeType;
    }

    public function getMimeType()
    {
        return $this->_mimeType;
    }
}