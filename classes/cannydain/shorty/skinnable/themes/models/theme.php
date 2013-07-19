<?php

namespace CannyDain\Shorty\Skinnable\Themes\Models;

class Theme
{
    protected $_name = '';
    protected $_template = '';
    protected $_stylesheets = array();
    protected $_id = 0;

    public function __construct($_id, $_name, $_template, $_stylesheets)
    {
        $this->_id = $_id;
        $this->_name = $_name;
        $this->_template = $_template;
        $this->_stylesheets = $_stylesheets;
    }

    public function setTemplate($template)
    {
        $this->_template = $template;
    }

    public function getTemplate()
    {
        return $this->_template;
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

    public function setStylesheets($stylesheets)
    {
        $this->_stylesheets = $stylesheets;
    }

    public function getStylesheets()
    {
        return $this->_stylesheets;
    }
}