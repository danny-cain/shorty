<?php

namespace CannyDain\Shorty\Sidebars;

use CannyDain\Shorty\Sidebars\Base\Sidebar;

class SidebarManager
{
    /**
     * @var Sidebar[]
     */
    protected $_sidebars = array();

    public function drawSidebars()
    {
        foreach ($this->_sidebars as $sidebar)
            $sidebar->drawSidebar();
    }

    public function addSidebar(Sidebar $sidebar)
    {
        $this->_sidebars[] = $sidebar;
    }
}