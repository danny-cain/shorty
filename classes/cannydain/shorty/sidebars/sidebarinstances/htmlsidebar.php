<?php

namespace CannyDain\Shorty\Sidebars\SidebarInstances;

use CannyDain\Shorty\Sidebars\Base\Sidebar;

class HTMLSidebar extends Sidebar
{
    protected $_title = '';
    protected $_content = '';

    public function __construct($title = '', $content = '')
    {
        $this->_title = $title;
        $this->_content = $content;
    }

    public function setContent($content)
    {
        $this->_content = $content;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    protected function _getTitle()
    {
        return $this->_title;
    }

    protected function _drawContent()
    {
        echo $this->_content;
    }
}