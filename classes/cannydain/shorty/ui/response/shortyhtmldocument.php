<?php

namespace CannyDain\Shorty\UI\Response;

use CannyDain\Lib\UI\Response\Document\HTMLDocument;
use CannyDain\Lib\UI\ResponsiveLayout\ResponsiveLayoutFactory;
use CannyDain\Shorty\Consumers\NavigationConsumer;
use CannyDain\Shorty\Consumers\ResponsiveLayoutConsumer;
use CannyDain\Shorty\Consumers\SidebarManagerConsumer;
use CannyDain\Shorty\Navigation\NavigationProvider;
use CannyDain\Shorty\Sidebars\SidebarManager;

class ShortyHTMLDocument extends HTMLDocument implements ResponsiveLayoutConsumer, NavigationConsumer, SidebarManagerConsumer
{
    /**
     * @var SidebarManager
     */
    protected $_sidebarManager;

    /**
     * @var ResponsiveLayoutFactory
     */
    protected $_responsiveLayoutFactory;

    /**
     * @var NavigationProvider
     */
    protected $_navigation;

    protected function _getExternalStylesheets()
    {
        return array
        (
            '/themes/blue.css',
        );
    }

    protected function _getScriptIncludes()
    {
        return array
        (
            '/scripts/jquery.min.js',
        );
    }

    protected function _writeInlineScripts()
    {
        echo <<<JAVASCRIPT

JAVASCRIPT;
    }

    protected function _getDocumentTitle()
    {
        return 'Shorty CMS';
    }

    protected function _displayPageHead()
    {
        echo '<body>';
            $this->_displaySiteHeader();

            echo '<div class="contentPane">';
                $this->_displaySidebar();
                echo '<div id="content">';
    }

    protected function _displaySidebar()
    {
        echo '<div class="sidebarContainer">';
            $this->_sidebarManager->drawSidebars();
        echo '</div>';
    }

    protected function _displaySiteHeader()
    {
            echo '<div id="header">';
                echo '<a href="/" id="logo">';
                    echo '<img src="/images/shorty-logo.png" />';
                echo '</a>';

                $this->_navigation->displayNavigation();
            echo '</div>';
    }

    protected function _displaySiteFooter()
    {
            echo '<div id="footer">';
                echo '<div id="copyright">';
                    echo '&copy; 2013 Canny Dain Web Factory';
                echo '</div>';
            echo '</div>';
    }

    protected function _displayPageFoot()
    {
                echo '</div>';
            echo '</div>';

            $this->_displaySiteFooter();
        echo '</body>';
    }

    protected function _writeInlineStyles()
    {

    }

    public function dependenciesConsumed()
    {

    }

    public function consumeResponsiveLayoutFactory(ResponsiveLayoutFactory $dependency)
    {
        $this->_responsiveLayoutFactory = $dependency;
    }

    public function consumeNavigationProvider(NavigationProvider $dependency)
    {
        $this->_navigation = $dependency;
    }

    public function consumeSidebarManager(SidebarManager $manager)
    {
        $this->_sidebarManager = $manager;
    }
}