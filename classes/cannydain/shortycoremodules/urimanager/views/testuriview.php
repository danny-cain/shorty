<?php

namespace CannyDain\ShortyCoreModules\URIManager\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\HTMLView;

class TestURIView extends HTMLView
{
    protected $_uri = '';

    /**
     * @var Route
     */
    protected $_route;

    protected $_submitURI = '';

    public function display()
    {
        echo '<form method="post" action="'.$this->_submitURI.'">';
            echo 'URI To Test: <input type="text" name="uri" value="'.$this->_uri.'" />';
            echo '<input type="submit" value="Test" />';
        echo '</form>';
        
        if ($this->_route != null)
        {
            echo '<pre>'.print_r($this->_route, true).'</pre>';
        }
    }

    public function setSubmitURI($submitURI)
    {
        $this->_submitURI = $submitURI;
    }

    public function getSubmitURI()
    {
        return $this->_submitURI;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $route
     */
    public function setRoute($route)
    {
        $this->_route = $route;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getRoute()
    {
        return $this->_route;
    }

    public function setUri($uri)
    {
        $this->_uri = $uri;
    }

    public function getUri()
    {
        return $this->_uri;
    }
}