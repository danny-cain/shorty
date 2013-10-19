<?php

namespace CannyDain\Shorty\UI;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\UI\Response\Layouts\Layout;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\Helpers\SessionHelper;

class ShortyLayout extends FramedViewLayout implements DependencyConsumer, RouterConsumer, SessionConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var SessionHelper
     */
    protected $_session;

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

    protected function _canDisplayLink($link)
    {
        if ($this->_isAdmin())
            return true;

        switch($link)
        {
            case '/cannydain-shortymodules-tasks-controllers-taskscontroller':
            case '/cannydain-shortymodules-tasks-cvlibrary-cvlibrarycontroller':
                if ($this->_session->getUserID() == 0)
                    return false;
                break;
            case '/cannydain-shortymodules-simpleshop-controllers-simpleshopadmincontroller':
            case '/cannydain-shortymodules-content-controllers-contentadmincontroller':
                return false;
                break;
        }

        return true;
    }

    protected function _displayNavigation()
    {
        $nav = array
        (
            '/' => 'Home',
            '/cannydain-shortymodules-users-controllers-usercontroller/login' => 'Login',
            '/cannydain-shortymodules-content-controllers-contentcontroller/view/1' => 'About',
            '/cannydain-shortymodules-simpleshop-controllers-simpleshopadmincontroller' => 'Shop Admin',
            '/cannydain-shortymodules-content-controllers-contentadmincontroller' => 'Content Admin',
            '/cannydain-shortymodules-tasks-controllers-taskscontroller' => 'Project Management',
            '/cannydain-shortymodules-cvlibrary-controllers-cvlibrarycontroller' => 'CV Library',
            '/cannydain-shortymodules-stories-controllers-storycontroller' => 'Stories',
        );

        echo '<nav id="mainNav">';
            foreach ($nav as $uri => $caption)
            {
                if (!$this->_canDisplayLink($uri))
                    continue;

                echo '<a href="'.$uri.'">'.$caption.'</a>';
            }

        /*
            echo '<a href="/">Home</a>';
            echo '<a href="/cannydain-shortymodules-todo-controllers-todocontroller">Todo</a>';
            echo '<a href="/cannydain-shortymodules-users-controllers-usercontroller/login">Login</a>';
            echo '<a href="/cannydain-shortymodules-content-controllers-contentcontroller/view/1">About</a>';
            echo '<a href="/cannydain-shortymodules-simpleshop-controllers-simpleshopadmincontroller">Shop Admin</a>';
            echo '<a href="/cannydain-shortymodules-content-controllers-contentadmincontroller">Content Admin</a>';
            echo '<a href="/cannydain-shortymodules-tasks-controllers-taskscontroller">Project Management</a>';
        */
        echo '</nav>';
    }

    protected function _displayPageFoot()
    {
            echo '</div>';
        echo '<div id="footerPane">';
            echo '&copy; 2013 Danny Cain';
        echo '</div>';

        $this->_displayProfilerInfo();
    }

    protected function _displayProfilerInfo()
    {
        if (!function_exists('xdebug_get_profiler_filename'))
            return;

        $filename = xdebug_get_profiler_filename();
        if ($filename == '')
            return;

        echo '<div id="profilerFilename">';
            echo 'profile: '.xdebug_get_profiler_filename();
        echo '</div>';
    }

    protected function _isAdmin()
    {
        return $this->_session->getUserID() == 1 || $this->_session->getUserID() == 2;
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

    public function consumeDependencies(DependencyInjector $dependencies)
    {
        $this->_dependencies = $dependencies;
    }

    public function consumeRouter(RouterInterface $router)
    {
        $this->_router = $router;
    }

    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }
}