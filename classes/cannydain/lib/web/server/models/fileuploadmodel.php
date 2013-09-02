<?php

namespace CannyDain\Lib\Web\Server\Models;

class FileUploadModel
{
    protected $_fieldname = '';
    protected $_index = 0;
    protected $_filename = '';
    protected $_tmpFile= '';
    protected $_mimeType = '';
    protected $_size = 0;

    public function setFieldname($fieldname)
    {
        $this->_fieldname = $fieldname;
    }

    public function getFieldname()
    {
        return $this->_fieldname;
    }

    public function setFilename($filename)
    {
        $this->_filename = $filename;
    }

    public function getFilename()
    {
        return $this->_filename;
    }

    public function setIndex($index)
    {
        $this->_index = $index;
    }

    public function getIndex()
    {
        return $this->_index;
    }

    public function setMimeType($mimeType)
    {
        $this->_mimeType = $mimeType;
    }

    public function getMimeType()
    {
        return $this->_mimeType;
    }

    public function setSize($size)
    {
        $this->_size = $size;
    }

    public function getSize()
    {
        return $this->_size;
    }

    public function setTmpFile($tmpFile)
    {
        $this->_tmpFile = $tmpFile;
    }

    public function getTmpFile()
    {
        return $this->_tmpFile;
    }
}