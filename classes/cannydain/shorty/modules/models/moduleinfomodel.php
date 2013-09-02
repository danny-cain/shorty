<?php

namespace CannyDain\Shorty\Modules\Models;

class ModuleInfoModel
{
    protected $_version = "";
    protected $_name = "";
    protected $_author = "";

    public function __construct($name = '', $author = '', $version = '0.1')
    {
        $this->_name = $name;
        $this->_author = $author;
        $this->_version = $version;
    }

    public function setAuthor($author)
    {
        $this->_author = $author;
    }

    public function getAuthor()
    {
        return $this->_author;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setVersion($version)
    {
        $this->_version = $version;
    }

    public function getVersion()
    {
        return $this->_version;
    }
}