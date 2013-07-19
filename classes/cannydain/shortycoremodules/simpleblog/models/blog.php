<?php

namespace CannyDain\ShortyCoreModules\SimpleBlog\Models;

class Blog
{
    protected $_id = 0;
    protected $_name = '';
    protected $_owner = 0;
    protected $_tagline = '';
    protected $_uri = '';

    public function setUri($uri)
    {
        $this->_uri = $uri;
    }

    public function getUri()
    {
        return $this->_uri;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setOwner($owner)
    {
        $this->_owner = $owner;
    }

    public function getOwner()
    {
        return $this->_owner;
    }

    public function setTagline($tagline)
    {
        $this->_tagline = $tagline;
    }

    public function getTagline()
    {
        return $this->_tagline;
    }
}