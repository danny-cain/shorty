<?php

namespace CannyDain\Shorty\Views\Errors;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Shorty\Exceptions\RoutingException;

class PageNotFoundView extends HTMLView
{
    /**
     * @var RoutingException
     */
    protected $_exception;

    function __construct($_exception = null)
    {
        $this->_exception = $_exception;
    }

    public function display()
    {
        echo '<h1>Not Found</h1>';
        echo '<p>The page you requested could not be found.</p>';
        $this->_exception->display();
    }

    public function setException($route)
    {
        $this->_exception = $route;
    }

    public function getException()
    {
        return $this->_exception;
    }
}