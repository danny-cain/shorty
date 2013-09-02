<?php

namespace CannyDain\Lib\UI\Response\Layouts;

use CannyDain\Lib\UI\Views\ViewInterface;

class NullLayout implements ViewInterface
{
    /**
     * @var ViewInterface
     */
    protected $_view;
    protected $_contentType ='';

    public function __construct($ct = '')
    {
        $this->_contentType= $ct;
    }

    public function display(ViewInterface $view = null)
    {
        header("Content-Type: ".$this->getContentType());
        $this->_view = $view;
        $view->display();
    }

    public function getContentType()
    {
        return $this->_contentType;
    }
}