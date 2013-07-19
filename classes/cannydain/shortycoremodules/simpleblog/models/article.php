<?php

namespace CannyDain\ShortyCoreModules\SimpleBlog\Models;

class Article
{
    protected $_id = 0;
    protected $_blog = 0;
    protected $_author = 0;
    protected $_posted = 0;
    protected $_title = '';
    protected $_content = '';
    protected $_tags = array();
    protected $_uri = '';

    public function setUri($uri)
    {
        $this->_uri = $uri;
    }

    public function getUri()
    {
        return $this->_uri;
    }

    public function setTags($tags)
    {
        $this->_tags = $tags;
    }

    public function getTags()
    {
        return $this->_tags;
    }

    public function setAuthor($author)
    {
        $this->_author = $author;
    }

    public function getAuthor()
    {
        return $this->_author;
    }

    public function setBlog($blog)
    {
        $this->_blog = $blog;
    }

    public function getBlog()
    {
        return $this->_blog;
    }

    public function setContent($content)
    {
        $this->_content = $content;
    }

    public function getContent()
    {
        return $this->_content;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setPosted($posted)
    {
        $this->_posted = $posted;
    }

    public function getPosted()
    {
        return $this->_posted;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getTitle()
    {
        return $this->_title;
    }
}