<?php

namespace CannyDain\Lib\UI\Response\Document;

use CannyDain\Lib\UI\Views\ViewInterface;

abstract class HTMLDocument extends BaseDocument
{
    protected function _displayDocumentHead()
    {
        echo '<!DOCTYPE html>';
        echo '<html>';
            echo '<head>';
                $this->_displayDocumentMeta();
            echo '</head>';
    }

    protected function _displayDocumentMeta()
    {
        echo '<title>'.$this->_getDocumentTitle().'</title>';

        foreach ($this->_getExternalStylesheets() as $stylesheet)
            echo '<link rel="stylesheet" type="text/css" href="'.$stylesheet.'" />';

        foreach ($this->_getScriptIncludes() as $script)
            echo '<script type="text/javascript" src="'.$script.'"></script>';

        $this->_writePostIncludesHead();

        echo '<style type="text/css">';
            $this->_writeInlineStyles();
        echo '</style>';

        echo '<script type="text/javascript">';
            $this->_writeInlineScripts();
        echo '</script>';
    }

    protected function _writePostIncludesHead() {}

    protected abstract function _writeInlineStyles();
    protected abstract function _writeInlineScripts();

    protected function _getExternalStylesheets()
    {
        return array();
    }

    protected function _getScriptIncludes()
    {
        return array();
    }

    protected function _displayDocumentFoot()
    {
        echo '</html>';
    }

    protected abstract function _getDocumentTitle();
}