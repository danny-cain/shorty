<?php

namespace CannyDain\ShortyCoreModules\SimpleContentModule\Views;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\ShortyCoreModules\SimpleContentModule\Models\ContentPage;

class PageListView extends HTMLView implements RouterConsumer
{
    /**
     * @var ContentPage[]
     */
    protected $_pages;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var string
     */
    protected $_viewPageURITemplate;

    /**
     * @param string $viewPageURITemplate
     */
    public function setViewPageURITemplate($viewPageURITemplate)
    {
        $this->_viewPageURITemplate = $viewPageURITemplate;
    }

    /**
     * @return string
     */
    public function getViewPageURITemplate()
    {
        return $this->_viewPageURITemplate;
    }

    public function setPages($pages)
    {
        $this->_pages = $pages;
    }

    public function getPages()
    {
        return $this->_pages;
    }

    public function display()
    {
        echo '<div class="simpleContentPageList">';
            echo '<ul>';
                foreach ($this->_pages as $page)
                {
                    $uri = strtr($this->_viewPageURITemplate, array('#id#' => $page->getFriendlyID()));
                    $route = $this->_router->getRoute($uri);
                    $uri = $this->_router->getURI($route);

                    echo '<li><a href="'.$uri.'">'.$page->getTitle().'</a></li>';
                }
            echo '</ul>';
        echo '</div>';
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }
}