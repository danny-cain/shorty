<?php

namespace CannyDain\Shorty\Navigation;

class StaticNavigation extends BaseNavigationProvider
{
    protected $_navigation = array();

    public function __construct($links = array())
    {
        $this->_navigation = $links;
    }

    public function displayNavigation($containerClasses = array())
    {
        echo '<nav class="'.implode(' ', $containerClasses).'">';
            foreach ($this->_navigation as $url => $caption)
                $this->_displayLink($url, $caption);
        echo '</nav>';
    }

    protected function _displayLink($url, $caption)
    {
        $classes = array();
        $classes[] = 'navItem';

        if ($this->_isCurrentPage($url))
            $classes[] = 'selected';

        $thisRoute = $this->_router->getURI($this->_router->getRoute($url));
        $currentRoute = $this->_router->getURI($this->_router->getRoute($this->_request->getResource()));

        echo '<a data-link-route="'.$thisRoute.'" data-current-route="'.$currentRoute.'" class="'.implode(' ', $classes).'" href="'.$url.'">'.$caption.'</a>';
    }
}