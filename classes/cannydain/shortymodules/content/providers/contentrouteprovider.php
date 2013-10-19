<?php

namespace CannyDain\ShortyModules\Content\Providers;

use CannyDain\Shorty\Routing\Models\RouteInfo;
use CannyDain\Shorty\Routing\RouteProvider;
use CannyDain\ShortyModules\Content\Controllers\ContentController;
use CannyDain\ShortyModules\Content\Datasource\ContentDatasource;
use CannyDain\ShortyModules\Content\Models\ContentPage;

class ContentRouteProvider implements RouteProvider
{
    /**
     * @var ContentDatasource
     */
    protected $_datasource;

    function __construct($_datasource)
    {
        $this->_datasource = $_datasource;
    }

    /**
     * Returns the name of the type this provider handles (classname)
     * @return string
     */
    public function getType()
    {
        return ContentPage::TYPE_NAME_CONTENT_PAGE;
    }

    /**
     * Returns a user friendly type name (i.e. blog, forum etc)
     * @return string
     */
    public function getTypeName()
    {
        return 'Content Page';
    }

    /**
     * @param $searchTerm
     * @return RouteInfo[]
     */
    public function search($searchTerm)
    {
        return array();
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->_datasource->getAllPages());
    }

    /**
     * @param int $page
     * @param int $resultsPerPage
     * @return RouteInfo[]
     */
    public function browse($page = 1, $resultsPerPage = 20)
    {
        $pages = $this->_datasource->getAllPages();
        $lowBound = ($page - 1) * $resultsPerPage;

        $ret = array();
        $pages = array_splice($pages, $lowBound, $resultsPerPage);
        foreach ($pages as $page)
        {
            /**
             * @var ContentPage $page
             */
            $ret[] = new RouteInfo($page->getTitle(), ContentPage::TYPE_NAME_CONTENT_PAGE, ContentController::CONTROLLER_NAME, 'View', array($page->getId()));
        }

        return $ret;
    }
}