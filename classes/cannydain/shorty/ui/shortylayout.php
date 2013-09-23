<?php

namespace CannyDain\Shorty\UI;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Response\Layouts\Layout;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\ShortyModules\Minifier\Controllers\MinifierController;
use CannyDain\ShortyModules\SimpleShop\Views\MiniBasketView;

class ShortyLayout extends Layout implements DependencyConsumer, RouterConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var RouterInterface
     */
    protected $_router;

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
        echo '<link rel="stylesheet" type="text/css" href="/styles.php" />';
    }

    protected function _outputScripts()
    {
        echo '<script type="application/javascript" src="/scripts.php"></script>';
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
            echo '<a href="/cannydain-shortymodules-simpleshop-controllers-simpleshopadmincontroller">Shop Admin</a>';
            echo '<a href="/cannydain-shortymodules-content-controllers-contentadmincontroller">Content Admin</a>';
            echo '<a href="/cannydain-shortymodules-tasks-controllers-taskscontroller">Project Management</a>';
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
}