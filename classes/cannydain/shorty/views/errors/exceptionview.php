<?php

namespace CannyDain\Shorty\Views\Errors;

use CannyDain\Lib\Exceptions\CannyLibException;
use CannyDain\Lib\UI\Views\HTMLView;
use Exception;

class ExceptionView extends HTMLView
{
    /**
     * @var Exception
     */
    protected $_exception;

    function __construct($_exception = null)
    {
        $this->_exception = $_exception;
    }

    public function display()
    {
        echo '<h1>Internal Server Error</h1>';
        echo '<p>An error has occured with the server.</p>';

        if ($this->_exception instanceof CannyLibException)
            $this->_exception->display();
        else
            echo '<p>'.$this->_exception->getMessage().'</p>';
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