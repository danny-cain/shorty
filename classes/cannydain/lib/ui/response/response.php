<?php

namespace CannyDain\Lib\UI\Response;

use CannyDain\Lib\UI\Response\Document\DocumentInterface;
use CannyDain\Lib\UI\Views\ViewInterface;

class Response
{
    /**
     * @var ViewInterface
     */
    protected $_view;
    protected $_cookies = array();

    /**
     * @var DocumentInterface
     */
    protected $_document;

    public function setDocument(DocumentInterface $doc)
    {
        $this->_document = $doc;
    }

    public function display()
    {
        $this->_document->display($this->_view);
    }

    public function setCookie($cookie, $value, $path = '/', $lifeSpan = null)
    {
        setcookie($cookie, $value, $lifeSpan, $path);
    }

    /**
     * @param \CannyDain\Lib\UI\Views\ViewInterface $view
     */
    public function setView($view)
    {
        $this->_view = $view;
    }
}