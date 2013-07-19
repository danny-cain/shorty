<?php

namespace CannyDain\Shorty\Sidebars\Base;

abstract class Sidebar implements SidebarInterface
{
    public function drawSidebar()
    {
        $title = $this->_getTitle();

        echo '<div class="sidebar">';
            if ($title != '')
                echo '<h2>'.$title.'</h2>';

            $this->_drawContent();
        echo '</div>';
    }

    protected abstract function _getTitle();
    protected abstract function _drawContent();
}