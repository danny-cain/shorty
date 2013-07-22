<?php

namespace CannyDain\ShortyCoreModules\AdminModule\Models;

class DashboardEntry
{
    protected $_id = 0;
    protected $_caption = '';
    protected $_text = '';
    protected $_moduleName = '';

    public function setModuleName($moduleName)
    {
        $this->_moduleName = $moduleName;
    }

    public function getModuleName()
    {
        return $this->_moduleName;
    }

    public function setCaption($caption)
    {
        $this->_caption = $caption;
    }

    public function getCaption()
    {
        return $this->_caption;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setText($text)
    {
        $this->_text = $text;
    }

    public function getText()
    {
        return $this->_text;
    }
}