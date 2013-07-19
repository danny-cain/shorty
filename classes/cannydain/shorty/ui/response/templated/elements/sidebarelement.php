<?php

namespace CannyDain\Shorty\UI\Response\Templated\Elements;

use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElement;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Consumers\SidebarManagerConsumer;
use CannyDain\Shorty\Sidebars\SidebarManager;

class SidebarElement extends TemplatedDocumentElement implements SidebarManagerConsumer
{
    /**
     * @var SidebarManager
     */
    protected $_sidebar;

    public function display(ViewInterface $view)
    {
        $this->_sidebar->drawSidebars();
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeSidebarManager(SidebarManager $manager)
    {
        $this->_sidebar = $manager;
    }
}