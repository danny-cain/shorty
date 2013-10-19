<?php

namespace CannyDain\Shorty\FileManager\Models;

class FileModel
{
    const TYPE_DIRECTORY = 'dir';
    const TYPE_FILE = 'file';

    protected $_path = '';
    protected $_name = '';
    protected $_webPath = '';
    protected $_type = 'file';

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setPath($path)
    {
        $this->_path = $path;
    }

    public function getPath()
    {
        return $this->_path;
    }

    public function setWebPath($webPath)
    {
        $this->_webPath = $webPath;
    }

    public function getWebPath()
    {
        return $this->_webPath;
    }

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function getType()
    {
        return $this->_type;
    }
}