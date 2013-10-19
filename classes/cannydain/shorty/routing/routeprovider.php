<?php

namespace CannyDain\Shorty\Routing;

use CannyDain\Shorty\Routing\Models\RouteInfo;

interface RouteProvider
{
    /**
     * Returns the name of the type this provider handles (classname)
     * @return string
     */
    public function getType();

    /**
     * Returns a user friendly type name (i.e. blog, forum etc)
     * @return string
     */
    public function getTypeName();

    /**
     * @param $searchTerm
     * @return RouteInfo[]
     */
    public function search($searchTerm);

    /**
     * @return int
     */
    public function count();

    /**
     * @param int $page
     * @param int $resultsPerPage
     * @return RouteInfo[]
     */
    public function browse($page = 1, $resultsPerPage = 20);
}