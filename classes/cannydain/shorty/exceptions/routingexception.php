<?php

namespace CannyDain\Shorty\Exceptions;

use CannyDain\Lib\Exceptions\CannyLibException;
use CannyDain\Lib\Routing\Models\Route;

class RoutingException extends CannyLibException
{
    /**
     * @var Route
     */
    protected $_route;
    protected $_message;

    public function __construct(Route $route, $message)
    {
        $this->_route = $route;
        $this->_message = $message;
        parent::__construct("Routing Failed: ".$message);
    }

    protected function _displayMessage()
    {
        parent::_displayMessage();

        echo '<div>';
            echo '<strong>Controller:</strong>'.$this->_route->getController();
        echo '</div>';

        echo '<div>';
            echo '<strong>Method:</strong>'.$this->_route->getMethod();
        echo '</div>';
    }


}