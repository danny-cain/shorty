<?php

namespace CannyDain\Shorty\Views;

use CannyDain\Lib\Exceptions\CannyLibException;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;

class ExceptionView extends HTMLView
{
    /**
     * @var string
     */
    protected $_uri;

    /**
     * @var \CannyDain\Lib\Routing\Models\Route
     */
    protected $_route;

    /**
     * @var \Exception
     */
    protected $_error = null;

    public function __construct($uri = '', Route $route = null, \Exception $exception = null)
    {
        $this->_uri = $uri;
        $this->_route = $route;
        $this->_error = $exception;
    }

    public function display()
    {
        echo '<h1>The page you requested could not be found</h1>';
        echo '<p>This is almost certainly our fault and we are working to fix it as soon as possible.</p>';

        if (!is_null($this->_route))
            echo $this->_route->getController().'::'.$this->_route->getMethod().'("'.implode('", "', $this->_route->getParams()).'")<br>';
        else
            echo 'URI: '.$this->_uri.'<br>';

        $this->_displayError();
    }

    protected function _displayError()
    {
        if ($this->_error == null)
            return;

        if ($this->_error instanceof CannyLibException)
            $this->_error->display();
        else
            echo 'Error: '.get_class($this->_error).'->'.$this->_error->getMessage();
    }
}