<?php

namespace CannyDain\Sandbox\Views;

use CannyDain\Lib\UI\Views\HTMLView;

class ContentView extends HTMLView
{
    protected $_title = '';
    protected $_content = '';

    public function display()
    {
        echo '<h1>'.$this->_title.'</h1>';
        echo $this->_content;
    }

    public function setContent($content)
    {
        $this->_content = $content;
    }

    public function getContent()
    {
        return $this->_content;
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