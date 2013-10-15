<?php

namespace Sandbox\Views;

use CannyDain\Shorty\Views\ShortyView;

class SandboxView extends ShortyView
{
    protected $_links = array();

    public function display()
    {
        echo '<h1>Sandbox</h1>';

        foreach ($this->_links as $link => $caption)
        {
            echo '<a style="display: inline-block; vertical-align: top; padding: 2px 5px; border: 1px solid black;" href="'.$link.'">'.$caption.'</a>';
        }
    }

    public function setLinks($links)
    {
        $this->_links = $links;
    }

    public function getLinks()
    {
        return $this->_links;
    }
}