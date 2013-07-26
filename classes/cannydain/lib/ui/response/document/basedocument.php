<?php

namespace CannyDain\Lib\UI\Response\Document;

use CannyDain\Lib\UI\Views\ViewInterface;

abstract class BaseDocument implements DocumentInterface
{
    /**
     * @var ViewInterface
     */
    protected $_view;

    protected abstract function _displayDocumentHead();
    protected abstract function _displayPageHead();

    protected abstract function _displayPageFoot();
    protected abstract function _displayDocumentFoot();

    public function display(ViewInterface $view = null)
    {
        $this->_view = $view;

        $this->_displayDocumentHead();
            $this->_displayPageHead();

            if ($view != null)
                $view->display();

            $this->_displayPageFoot();
        $this->_displayDocumentFoot();
    }
}