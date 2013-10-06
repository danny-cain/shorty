<?php

namespace CannyDain\Shorty\Views\Errors;

use CannyDain\Lib\Execution\Exceptions\NotAuthorisedException;
use CannyDain\Lib\UI\Views\HTMLView;

class NotAuthorisedView extends HTMLView
{
    /**
     * @var NotAuthorisedException
     */
    protected $_exception;

    function __construct($_exception = null)
    {
        $this->_exception = $_exception;
    }

    public function display()
    {
        echo '<h1>Denied</h1>';
        echo '<p>You do not have permission to access this page.</p>';
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