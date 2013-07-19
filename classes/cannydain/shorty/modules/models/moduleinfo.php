<?php

namespace CannyDain\Shorty\Modules\Models;

class ModuleInfo
{
    protected $_name = '';
    protected $_author = '';
    protected $_version = '1.0.0';
    protected $_releaseDate = 0;
    protected $_authorWebsite = '';

    public function setAuthor($author)
    {
        $this->_author = $author;
    }

    public function getAuthor()
    {
        return $this->_author;
    }

    public function setAuthorWebsite($authorWebsite)
    {
        $this->_authorWebsite = $authorWebsite;
    }

    public function getAuthorWebsite()
    {
        return $this->_authorWebsite;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setReleaseDate($releaseDate)
    {
        $this->_releaseDate = $releaseDate;
    }

    public function getReleaseDate()
    {
        return $this->_releaseDate;
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