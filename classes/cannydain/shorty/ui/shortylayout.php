<?php

namespace CannyDain\Shorty\UI;

use CannyDain\Lib\UI\Response\Layouts\Layout;

class ShortyLayout extends Layout
{
    protected function _displayDocumentHead()
    {
        echo '<!DOCTYPE html>';
        echo '<html>';
            echo '<head>';
                echo '<title>Shorty Mk II</title>';
                $this->_outputStylesheets();
                $this->_outputScripts();
            echo '</head>';

            echo '<body>';
    }

    protected function _outputStylesheets()
    {
        echo '<link rel="stylesheet" type="text/css" href="/styles/layout.css" />';
        echo '<link rel="stylesheet" type="text/css" href="/styles/styles.css" />';
        echo '<link rel="stylesheet" type="text/css" href="/styles/forms.css" />';
        echo '<link rel="stylesheet" type="text/css" href="/styles/comments.css" />';
    }

    protected function _outputScripts()
    {

    }

    protected function _displayPageHead()
    {
        echo '<div id="headerPane">';
            $this->_displayLogo();
            $this->_displayNavigation();
        echo '</div>';

        echo '<div id="centerPane">';
    }

    protected function _displayLogo()
    {
        echo '<a href="/" id="homeLink">';
            echo '<img src="/images/logo.png" />';
        echo '</a>';
    }

    protected function _displayNavigation()
    {
        echo '<nav id="mainNav">';
            echo '<a href="/">Home</a>';
            echo '<a href="/cannydain-shortymodules-todo-controllers-todocontroller">Todo</a>';
            echo '<a href="/cannydain-shortymodules-users-controllers-usercontroller/login">Login</a>';
            echo '<a href="/cannydain-shortymodules-content-controllers-contentcontroller/view/1">About</a>';
        echo '</nav>';
    }

    protected function _displayPageFoot()
    {
            echo '</div>';
        echo '<div id="footerPane">';
            echo '&copy; 2013 Danny Cain';
        echo '</div>';
    }

    protected function _displayDocumentFoot()
    {
            echo '</body>';
        echo '</html>';
    }

    public function getContentType()
    {
        return self::CONTENT_TYPE_HTML;
    }
}