<?php

namespace CannyDain\Lib\UI\Response\Document;

use CannyDain\Lib\UI\Response\Document\DocumentInterface;
use CannyDain\Lib\UI\Views\JSONView;
use CannyDain\Lib\UI\Views\TitledView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\SidebarManagerConsumer;
use CannyDain\Shorty\Sidebars\SidebarManager;

class JSONContentOnlyDocument implements DocumentInterface, RequestConsumer, SidebarManagerConsumer
{
    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var SidebarManager
     */
    protected $_sidebars;

    public function display(ViewInterface $view)
    {
        $content = $this->_getContent($view);
        $title = $this->_getSiteTitle($view);
        $data = array('content' => $content, 'title' => $title, 'sidebar' => $this->_hasSidebar($view));

        $data['sidebar-content'] = $this->_getSidebarContent();

        $view = new JSONView($data);
        $view->display();
    }

    protected function _getSidebarContent()
    {
        ob_start();
            $this->_sidebars->drawSidebars();
        return ob_get_clean();
    }

    protected function _getContent(ViewInterface $view)
    {
        ob_start();
            $view->display();
        return ob_get_clean();
    }

    protected function _getSiteTitle(ViewInterface $view)
    {
        $title = 'Shorty';
        if ($view instanceof TitledView)
            $title .= ' - '.$view->getTitle();

        return $title;
    }

    protected function _hasSidebar(ViewInterface $view)
    {
        return true;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }

    public function consumeSidebarManager(SidebarManager $manager)
    {
        $this->_sidebars = $manager;
    }
}